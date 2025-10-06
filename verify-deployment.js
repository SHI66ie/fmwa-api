/**
 * Deployment Verification Script
 * Tests if visitor counter is working on production
 */

const https = require('https');
const http = require('http');

const PRODUCTION_URL = 'www.womenaffairs.gov.ng';
const USE_HTTPS = false; // Changed to HTTP due to expired SSL certificate

console.log('🔍 Verifying Visitor Counter Deployment...\n');

// Test API endpoint
function testAPI(endpoint) {
    return new Promise((resolve, reject) => {
        const protocol = USE_HTTPS ? https : http;
        const url = `${USE_HTTPS ? 'https' : 'http'}://${PRODUCTION_URL}${endpoint}`;
        
        console.log(`Testing: ${url}`);
        
        protocol.get(url, (res) => {
            let data = '';
            
            res.on('data', (chunk) => {
                data += chunk;
            });
            
            res.on('end', () => {
                if (res.statusCode === 200) {
                    try {
                        const json = JSON.parse(data);
                        resolve({ success: true, status: res.statusCode, data: json });
                    } catch (e) {
                        resolve({ success: false, status: res.statusCode, error: 'Invalid JSON', data });
                    }
                } else {
                    resolve({ success: false, status: res.statusCode, data });
                }
            });
        }).on('error', (err) => {
            reject(err);
        });
    });
}

// Test if file exists
function testFile(path) {
    return new Promise((resolve, reject) => {
        const protocol = USE_HTTPS ? https : http;
        const url = `${USE_HTTPS ? 'https' : 'http'}://${PRODUCTION_URL}${path}`;
        
        console.log(`Testing: ${url}`);
        
        protocol.get(url, (res) => {
            if (res.statusCode === 200) {
                resolve({ success: true, status: res.statusCode });
            } else {
                resolve({ success: false, status: res.statusCode });
            }
        }).on('error', (err) => {
            reject(err);
        });
    });
}

// Run all tests
async function runTests() {
    const tests = [
        {
            name: 'Homepage',
            test: () => testFile('/'),
            required: true
        },
        {
            name: 'Visitor Counter JS',
            test: () => testFile('/js/visitor-counter.js'),
            required: true
        },
        {
            name: 'Visitor Counter CSS',
            test: () => testFile('/css/visitor-counter.css'),
            required: true
        },
        {
            name: 'API: Get Visitor Count',
            test: () => testAPI('/api/visitor-count'),
            required: true
        },
        {
            name: 'Test Page',
            test: () => testFile('/test-visitor-counter.html'),
            required: false
        }
    ];
    
    let passed = 0;
    let failed = 0;
    let warnings = 0;
    
    for (const testCase of tests) {
        try {
            console.log(`\n📋 ${testCase.name}...`);
            const result = await testCase.test();
            
            if (result.success) {
                console.log(`✅ PASS - Status: ${result.status}`);
                if (result.data) {
                    console.log(`   Data:`, JSON.stringify(result.data, null, 2));
                }
                passed++;
            } else {
                if (testCase.required) {
                    console.log(`❌ FAIL - Status: ${result.status}`);
                    if (result.error) {
                        console.log(`   Error: ${result.error}`);
                    }
                    if (result.data && typeof result.data === 'string') {
                        console.log(`   Response: ${result.data.substring(0, 200)}...`);
                    }
                    failed++;
                } else {
                    console.log(`⚠️  WARNING - Status: ${result.status} (Optional)`);
                    warnings++;
                }
            }
        } catch (error) {
            if (testCase.required) {
                console.log(`❌ ERROR - ${error.message}`);
                failed++;
            } else {
                console.log(`⚠️  WARNING - ${error.message} (Optional)`);
                warnings++;
            }
        }
    }
    
    // Summary
    console.log('\n' + '='.repeat(60));
    console.log('📊 DEPLOYMENT VERIFICATION SUMMARY');
    console.log('='.repeat(60));
    console.log(`✅ Passed:   ${passed}`);
    console.log(`❌ Failed:   ${failed}`);
    console.log(`⚠️  Warnings: ${warnings}`);
    console.log('='.repeat(60));
    
    if (failed === 0) {
        console.log('\n🎉 SUCCESS! Visitor counter is deployed and working!');
        console.log(`\n🌐 Visit: ${USE_HTTPS ? 'https' : 'http'}://${PRODUCTION_URL}`);
        console.log('   Look for the visitor counter in the bottom-right corner.\n');
    } else {
        console.log('\n❌ DEPLOYMENT ISSUES DETECTED!');
        console.log('\n📝 Next Steps:');
        console.log('1. Check if files are uploaded to production server');
        console.log('2. Verify Node.js application is running');
        console.log('3. Check server logs for errors');
        console.log('4. Ensure data/ directory exists and is writable');
        console.log('5. Review DEPLOYMENT_CHECKLIST.md for detailed steps\n');
    }
    
    process.exit(failed > 0 ? 1 : 0);
}

// Run the tests
runTests().catch(err => {
    console.error('\n❌ Fatal Error:', err.message);
    console.log('\n💡 Tip: Make sure you have internet connection and the domain is accessible.\n');
    process.exit(1);
});
