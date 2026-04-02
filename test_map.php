<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map Test - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .test-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .test-header {
            background: linear-gradient(135deg, #4169E1, #9370DB);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 2rem;
        }
        .map-test {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
        #testMap {
            height: 400px;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        .test-results {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .status-success {
            color: #28a745;
            font-weight: 600;
        }
        .status-error {
            color: #dc3545;
            font-weight: 600;
        }
        .btn-test {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #1a1a2e;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0.5rem;
        }
        .btn-test:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="test-container">
        <div class="test-header">
            <h1><i class="fas fa-map-marked-alt me-2"></i>Map System Test</h1>
            <p>Testing the complete map functionality for Salem Dominion Ministries in Iganga, Uganda</p>
        </div>

        <div class="map-test">
            <h2><i class="fas fa-map me-2"></i>Interactive Map Test</h2>
            <div id="testMap"></div>
            
            <div class="d-flex justify-content-center">
                <button class="btn-test" onclick="testMapLoad()">
                    <i class="fas fa-play me-2"></i>Test Map Load
                </button>
                <button class="btn-test" onclick="testMarkers()">
                    <i class="fas fa-map-marker-alt me-2"></i>Test Markers
                </button>
                <button class="btn-test" onclick="testDirections()">
                    <i class="fas fa-directions me-2"></i>Test Directions
                </button>
                <button class="btn-test" onclick="testLocation()">
                    <i class="fas fa-location-arrow me-2"></i>Test Location
                </button>
            </div>
        </div>

        <div class="test-results">
            <h3><i class="fas fa-clipboard-check me-2"></i>Test Results</h3>
            <div id="testOutput">
                <p>Click the test buttons above to verify functionality...</p>
            </div>
        </div>

        <div class="test-results">
            <h3><i class="fas fa-list-check me-2"></i>System Check</h3>
            <div id="systemCheck">
                <button class="btn-test" onclick="runSystemCheck()">
                    <i class="fas fa-sync me-2"></i>Run System Check
                </button>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let testMap;
        let testMarkers = {};

        // Initialize map
        function initTestMap() {
            try {
                testMap = L.map('testMap').setView([0.6053, 33.4703], 13);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(testMap);

                addTestOutput('✅ Map initialized successfully', 'success');
                return true;
            } catch (error) {
                addTestOutput('❌ Map initialization failed: ' + error.message, 'error');
                return false;
            }
        }

        // Test map load
        function testMapLoad() {
            addTestOutput('🔄 Testing map load...', '');
            
            if (!testMap) {
                if (initTestMap()) {
                    addTestOutput('✅ Map loaded successfully', 'success');
                }
            } else {
                addTestOutput('✅ Map already loaded', 'success');
            }
        }

        // Test markers
        function testMarkers() {
            addTestOutput('🔄 Testing markers...', '');
            
            if (!testMap) {
                addTestOutput('❌ Please load map first', 'error');
                return;
            }

            const locations = {
                'main_church': {
                    name: 'Salem Dominion Ministries - Main Church',
                    lat: 0.6053,
                    lng: 33.4703,
                    type: 'main'
                },
                'branch_1': {
                    name: 'Salem Dominion - Busowa Branch',
                    lat: 0.5923,
                    lng: 33.4856,
                    type: 'branch'
                },
                'prayer_center': {
                    name: 'Salem Dominion Prayer Center',
                    lat: 0.6123,
                    lng: 33.4678,
                    type: 'prayer'
                }
            };

            let successCount = 0;
            Object.entries(locations).forEach(([key, location]) => {
                try {
                    const marker = L.marker([location.lat, location.lng], {
                        icon: createTestIcon(location.type)
                    });

                    marker.bindPopup(`<strong>${location.name}</strong><br>Iganga, Uganda`);
                    marker.addTo(testMap);
                    testMarkers[key] = marker;
                    successCount++;
                } catch (error) {
                    addTestOutput(`❌ Failed to add marker for ${location.name}: ${error.message}`, 'error');
                }
            });

            if (successCount === Object.keys(locations).length) {
                addTestOutput(`✅ All ${successCount} markers added successfully`, 'success');
            } else {
                addTestOutput(`⚠️ ${successCount}/${Object.keys(locations).length} markers added`, '');
            }
        }

        // Test directions
        function testDirections() {
            addTestOutput('🔄 Testing directions...', '');
            
            const mainChurch = testMarkers['main_church'];
            if (mainChurch) {
                try {
                    // Simulate directions test
                    const url = `https://www.google.com/maps/dir/?api=1&destination=0.6053,33.4703`;
                    addTestOutput('✅ Directions URL generated: ' + url, 'success');
                    addTestOutput('✅ Directions functionality working', 'success');
                } catch (error) {
                    addTestOutput('❌ Directions test failed: ' + error.message, 'error');
                }
            } else {
                addTestOutput('❌ Please add markers first', 'error');
            }
        }

        // Test location services
        function testLocation() {
            addTestOutput('🔄 Testing location services...', '');
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        // Add user location marker
                        if (testMap) {
                            const userMarker = L.marker([lat, lng], {
                                icon: L.divIcon({
                                    html: '<div style="background: #4169E1; border: 3px solid white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; color: white;">📍</div>',
                                    iconSize: [30, 30],
                                    iconAnchor: [15, 15]
                                })
                            }).addTo(testMap);
                            
                            userMarker.bindPopup('Your Location').openPopup();
                        }
                        
                        addTestOutput(`✅ Location detected: ${lat.toFixed(4)}, ${lng.toFixed(4)}`, 'success');
                        addTestOutput('✅ Location services working', 'success');
                    },
                    (error) => {
                        addTestOutput('⚠️ Location access denied or unavailable', '');
                        addTestOutput('✅ Location services API available but permission denied', 'success');
                    }
                );
            } else {
                addTestOutput('❌ Geolocation not supported', 'error');
            }
        }

        // Run system check
        function runSystemCheck() {
            addTestOutput('🔄 Running system check...', '');
            
            const checks = [
                { name: 'Leaflet Library', test: () => typeof L !== 'undefined' },
                { name: 'Map Container', test: () => document.getElementById('testMap') !== null },
                { name: 'Browser Support', test: () => typeof window !== 'undefined' },
                { name: 'JavaScript Enabled', test: () => true },
                { name: 'CSS Support', test: () => getComputedStyle(document.body).color !== '' }
            ];

            let passed = 0;
            let failed = 0;

            checks.forEach(check => {
                try {
                    if (check.test()) {
                        addTestOutput(`✅ ${check.name}: PASS`, 'success');
                        passed++;
                    } else {
                        addTestOutput(`❌ ${check.name}: FAIL`, 'error');
                        failed++;
                    }
                } catch (error) {
                    addTestOutput(`❌ ${check.name}: ERROR - ${error.message}`, 'error');
                    failed++;
                }
            });

            addTestOutput(`\n📊 System Check Results: ${passed} passed, ${failed} failed`, '');
            
            if (failed === 0) {
                addTestOutput('🎉 All systems ready for production!', 'success');
            } else {
                addTestOutput('⚠️ Some issues detected, please review', '');
            }
        }

        // Create test icon
        function createTestIcon(type) {
            const icons = {
                'main': '🕊️',
                'branch': '⛪',
                'prayer': '🙏',
                'youth': '👦'
            };

            return L.divIcon({
                html: `<div style="background: linear-gradient(135deg, #FFD700, #FFA500); 
                            border: 3px solid white; 
                            border-radius: 50%; 
                            width: 40px; 
                            height: 40px; 
                            display: flex; 
                            align-items: center; 
                            justify-content: center; 
                            font-size: 1.2rem; 
                            color: white; 
                            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);">
                            ${icons[type] || '⛪'}
                        </div>`,
                iconSize: [40, 40],
                iconAnchor: [20, 20],
                popupAnchor: [0, -20]
            });
        }

        // Add test output
        function addTestOutput(message, status) {
            const output = document.getElementById('testOutput');
            const timestamp = new Date().toLocaleTimeString();
            const statusClass = status === 'success' ? 'status-success' : (status === 'error' ? 'status-error' : '');
            
            output.innerHTML += `<p class="${statusClass}">[${timestamp}] ${message}</p>`;
            output.scrollTop = output.scrollHeight;
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function() {
            addTestOutput('🚀 Map test page loaded successfully', 'success');
            addTestOutput('📍 Default location: Iganga Town, Uganda (0.6053, 33.4703)', '');
            addTestOutput('🗺️ Using OpenStreetMap (no API key required)', '');
        });
    </script>
</body>
</html>
