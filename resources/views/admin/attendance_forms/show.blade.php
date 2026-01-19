@extends('admin_layout.app')

@section('title', 'Attendance Form - ' . $form->venue)

@section('head')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js"></script>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-6 xl:px-8 py-4 sm:py-8">
    <!-- Header -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $form->venue }}</h1>
                <p class="mt-1 sm:mt-2 text-sm sm:text-base text-gray-600">{{ $form->date->format('M d, Y') }} • {{ $form->records()->count() }} attendees</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <button id="cameraBtn" onclick="startCameraScanning()" 
                        class="px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium rounded-lg hover:shadow-lg transition active:scale-95">
                    <i class="fi fi-rr-camera mr-2"></i>Camera Scan
                </button>
                <button id="keyboardBtn" onclick="toggleKeyboardMode()"
                        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-medium rounded-lg hover:shadow-lg transition active:scale-95">
                    <i class="fi fi-rr-keyboard mr-2"></i>Hardware Scanner
                </button>
            </div>
        </div>
    </div>

    <!-- Scanning Mode Status -->
    <div id="modeStatus" class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200 p-4 sm:p-6 mb-6 sm:mb-8">
        <p class="text-center font-semibold text-gray-900">
            <span id="modeText">Keyboard Scanner Mode</span> - <span id="modeDesc" class="text-sm text-gray-600">Ready to scan QR codes using hardware scanner</span>
        </p>
    </div>

    <!-- Camera Scanner Modal -->
    <div id="cameraModal" style="display:none;" class="fixed inset-0 bg-black bg-opacity-20 z-50 flex items-center justify-center p-2 sm:p-4">
        <div class="bg-white rounded-lg w-full max-w-md p-3 sm:p-4">
            <div class="flex justify-between items-center mb-2 sm:mb-3">
                <h3 class="text-base sm:text-lg font-bold text-gray-900">Camera QR Scanner</h3>
                <button onclick="stopCameraScanning()" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">
                    ✕
                </button>
            </div>
            <div id="qr-reader" style="width: 100%; height: 250px; border: 2px solid #3b82f6; border-radius: 8px; overflow: hidden; background: #000;"></div>
            <p class="text-center text-xs sm:text-sm text-gray-600 mt-2 sm:mt-3">Point camera at QR code</p>
        </div>
    </div>

    <!-- Form Details -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200 p-4 sm:p-6 mb-6 sm:mb-8">
        <h3 class="font-semibold text-gray-900 mb-2">Activities Conducted</h3>
        <p class="text-gray-700">{{ $form->activities_conducted }}</p>
    </div>

    <!-- Attendance Records Table -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        @if($records->count() > 0)
            <div class="overflow-x-auto -mx-4 sm:mx-0">
                <table class="w-full min-w-max sm:min-w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 sm:px-6 py-4 text-left text-sm font-semibold text-gray-900">Name</th>
                            <th class="hidden sm:table-cell px-4 sm:px-6 py-4 text-left text-sm font-semibold text-gray-900">Gender</th>
                            <th class="hidden md:table-cell px-4 sm:px-6 py-4 text-left text-sm font-semibold text-gray-900">Contact</th>
                            <th class="hidden lg:table-cell px-4 sm:px-6 py-4 text-left text-sm font-semibold text-gray-900">Family Support</th>
                            <th class="px-4 sm:px-6 py-4 text-left text-sm font-semibold text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($records as $record)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 sm:px-6 py-4">
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">{{ $record->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $record->signature }}</p>
                                    </div>
                                </td>
                                <td class="hidden sm:table-cell px-4 sm:px-6 py-4">
                                    <span class="text-sm text-gray-600 capitalize">{{ $record->gender }}</span>
                                </td>
                                <td class="hidden md:table-cell px-4 sm:px-6 py-4">
                                    <p class="text-sm text-gray-600">{{ $record->contact_number }}</p>
                                </td>
                                <td class="hidden lg:table-cell px-4 sm:px-6 py-4">
                                    <p class="text-sm text-gray-600">{{ $record->family_support ?? '-' }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-4">
                                    <button onclick="deleteRecord({{ $record->id }})"
                                            class="px-3 py-1.5 bg-red-50 text-red-700 border border-red-200 text-xs sm:text-sm font-medium rounded-lg hover:bg-red-100 transition whitespace-nowrap">
                                        <i class="fi fi-rr-trash mr-1.5"></i><span class="hidden sm:inline">Delete</span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
                {{ $records->links() }}
            </div>
        @else
            <div class="px-4 sm:px-6 py-12 text-center">
                <div class="mb-4">
                    <i class="fi fi-rr-check-circle text-6xl text-gray-300"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Records Yet</h3>
                <p class="text-gray-600">Click "Start QR Scan" to record attendees</p>
            </div>
        @endif
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.attendance_forms.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">
            <i class="fi fi-rr-arrow-left mr-2"></i>Back to Forms
        </a>
    </div>
</div>

<script>
    const formId = {{ $form->id }};
    let qrInputBuffer = '';
    let qrInputTimeout = null;
    let html5QrcodeScanner = null;
    let keyboardModeActive = true;
    let cameraActive = false;

    // Listen for keyboard input from hardware QR scanner
    document.addEventListener('keydown', handleQrScannerInput);

    function toggleKeyboardMode() {
        keyboardModeActive = !keyboardModeActive;
        const btn = document.getElementById('keyboardBtn');
        const modeStatus = document.getElementById('modeStatus');
        const modeText = document.getElementById('modeText');
        const modeDesc = document.getElementById('modeDesc');
        
        if (keyboardModeActive) {
            btn.classList.remove('opacity-50');
            btn.innerHTML = '<i class="fi fi-rr-keyboard mr-2"></i>Hardware Scanner';
            modeText.textContent = 'Keyboard Scanner Mode';
            modeDesc.textContent = 'Ready to scan QR codes using hardware scanner';
            modeStatus.classList.remove('from-red-50', 'to-orange-50', 'border-red-200');
            modeStatus.classList.add('from-blue-50', 'to-indigo-50', 'border-blue-200');
        } else {
            btn.classList.add('opacity-50');
            btn.innerHTML = '<i class="fi fi-rr-keyboard mr-2"></i>Hardware Scanner (Off)';
            modeText.textContent = 'Hardware Scanner Disabled';
            modeDesc.textContent = 'Use camera scanner instead';
            modeStatus.classList.remove('from-blue-50', 'to-indigo-50', 'border-blue-200');
            modeStatus.classList.add('from-red-50', 'to-orange-50', 'border-red-200');
        }
        
        if (cameraActive) {
            Swal.fire({
                title: 'Camera Scanner Active',
                text: 'Please close the camera scanner first before disabling hardware mode',
                icon: 'info'
            });
            keyboardModeActive = true;
        }
    }

    function handleQrScannerInput(event) {
        if (!keyboardModeActive || cameraActive) return;

        // QR scanners typically send input rapidly followed by Enter key
        if (event.key === 'Enter' && qrInputBuffer.length > 0) {
            // QR code complete (scanner sends Enter after code)
            event.preventDefault();
            let qrCode = qrInputBuffer.trim();
            qrInputBuffer = '';
            
            console.log('Raw QR code scanned:', qrCode);
            
            // Extract token from URL if it's a full URL
            if (qrCode.includes('token=')) {
                try {
                    const url = new URL(qrCode);
                    qrCode = url.searchParams.get('token');
                    console.log('Extracted token from URL:', qrCode);
                } catch (e) {
                    console.error('Failed to parse URL:', e);
                }
            }
            
            if (qrCode && qrCode.length > 0) {
                validateQr(qrCode);
            }
            return;
        }
        
        // Accumulate characters into buffer (QR scanners type very fast)
        if (event.key.length === 1 && event.key !== ' ') {
            qrInputBuffer += event.key;
            
            // Reset timeout for end of input
            clearTimeout(qrInputTimeout);
            qrInputTimeout = setTimeout(() => {
                // If we have content but no Enter pressed within 100ms, it might still be a QR code
                if (qrInputBuffer.length > 5) { // QR codes are usually longer than 5 chars
                    let qrCode = qrInputBuffer.trim();
                    qrInputBuffer = '';
                    console.log('Raw QR code detected:', qrCode);
                    
                    // Extract token from URL if it's a full URL
                    if (qrCode.includes('token=')) {
                        try {
                            const url = new URL(qrCode);
                            qrCode = url.searchParams.get('token');
                            console.log('Extracted token from URL:', qrCode);
                        } catch (e) {
                            console.error('Failed to parse URL:', e);
                        }
                    }
                    
                    if (qrCode && qrCode.length > 0) {
                        validateQr(qrCode);
                    }
                }
            }, 100);
        }
    }

    function startCameraScanning() {
        cameraActive = true;
        
        // Get available cameras first
        Html5Qrcode.getCameras().then(devices => {
            console.log('Available cameras:', devices);
            
            if (devices && devices.length > 0) {
                let selectedCameraId = devices[devices.length - 1].id; // Default to last (external camera)
                
                if (devices.length > 1) {
                    // Show camera selection dialog
                    let cameraOptions = '<div style="text-align: left;">';
                    cameraOptions += '<p style="margin-bottom: 15px; font-weight: bold; font-size: 16px;">Select Camera:</p>';
                    devices.forEach((device, index) => {
                        const isExternal = index === devices.length - 1;
                        const isChecked = isExternal ? 'checked' : '';
                        cameraOptions += `<label style="display: block; margin: 10px 0; cursor: pointer; padding: 8px; border-radius: 4px; ${isChecked ? 'background: #eff6ff;' : ''}">
                            <input type="radio" name="camera" value="${device.id}" ${isChecked} style="margin-right: 10px; cursor: pointer;">
                            <span style="cursor: pointer;">${device.label || 'Camera ' + (index + 1)}</span>
                        </label>`;
                    });
                    cameraOptions += '</div>';
                    
                    Swal.fire({
                        title: 'Select Camera',
                        html: cameraOptions,
                        allowOutsideClick: false,
                        allowEscapeKey: true,
                        confirmButtonText: 'Start Scanning',
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#6b7280'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            selectedCameraId = document.querySelector('input[name="camera"]:checked').value;
                            openCameraModal(selectedCameraId);
                        } else {
                            cameraActive = false;
                        }
                    });
                } else {
                    // Single camera, open directly
                    openCameraModal(devices[0].id);
                }
            } else {
                Swal.fire({
                    title: 'No Camera Found',
                    text: 'Please make sure your camera is connected and permitted.',
                    icon: 'error',
                    confirmButtonColor: '#EF4444'
                });
                cameraActive = false;
            }
        }).catch(err => {
            console.error('Error getting cameras:', err);
            Swal.fire({
                title: 'Camera Error',
                text: 'Unable to access camera devices. ' + err.message,
                icon: 'error',
                confirmButtonColor: '#EF4444'
            });
            cameraActive = false;
        });
    }

    function openCameraModal(cameraId) {
        document.getElementById('cameraModal').style.display = 'flex';
        setTimeout(() => {
            initializeCamera(cameraId);
        }, 100);
    }

    function stopCameraScanning() {
        cameraActive = false;
        document.getElementById('cameraModal').style.display = 'none';
        
        const modeStatus = document.getElementById('modeStatus');
        const modeText = document.getElementById('modeText');
        const modeDesc = document.getElementById('modeDesc');
        
        modeStatus.classList.remove('from-purple-50', 'to-pink-50', 'border-purple-200');
        modeStatus.classList.add('from-blue-50', 'to-indigo-50', 'border-blue-200');
        modeText.textContent = 'Keyboard Scanner Mode';
        modeDesc.textContent = 'Ready to scan QR codes using hardware scanner';
        
        if (html5QrcodeScanner) {
            html5QrcodeScanner.stop().catch(err => console.log('Error stopping scanner:', err));
            html5QrcodeScanner = null;
        }
    }

    function initializeCamera(cameraId) {
        const modeStatus = document.getElementById('modeStatus');
        const modeText = document.getElementById('modeText');
        const modeDesc = document.getElementById('modeDesc');
        
        modeStatus.classList.remove('from-blue-50', 'to-indigo-50', 'border-blue-200');
        modeStatus.classList.add('from-purple-50', 'to-pink-50', 'border-purple-200');
        modeText.textContent = 'Camera Scanner Mode';
        modeDesc.textContent = 'Scanning with camera - point at QR code';
        
        try {
            const qrReaderElement = document.getElementById('qr-reader');
            if (!qrReaderElement) {
                throw new Error('QR reader element not found');
            }

            // Clear any existing scanner
            if (html5QrcodeScanner) {
                try {
                    html5QrcodeScanner.stop();
                } catch (e) {
                    console.log('Clearing old scanner');
                }
            }

            html5QrcodeScanner = new Html5Qrcode('qr-reader');
            
            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                console.log('Camera QR decoded:', decodedText);
                let qrCode = decodedText.trim();
                
                // Extract token from URL if it's a full URL
                if (qrCode.includes('token=')) {
                    try {
                        const url = new URL(qrCode);
                        qrCode = url.searchParams.get('token');
                        console.log('Extracted token from URL:', qrCode);
                    } catch (e) {
                        console.error('Failed to parse URL:', e);
                    }
                }
                
                // Stop scanning and validate
                stopCameraScanning();
                validateQr(qrCode);
            };
            
            const config = { 
                fps: 15,
                qrbox: 300
            };
            
            const constraints = {
                deviceId: { exact: cameraId }
            };
            
            html5QrcodeScanner.start(
                constraints,
                config,
                qrCodeSuccessCallback,
                errorMessage => {
                    console.log('Scanning error:', errorMessage);
                }
            ).catch(err => {
                console.error('Camera initialization failed:', err);
                Swal.fire({
                    title: 'Camera Error',
                    text: 'Unable to start camera: ' + err.message,
                    icon: 'error',
                    confirmButtonColor: '#EF4444'
                });
                stopCameraScanning();
            });
        } catch (err) {
            console.error('Error initializing camera:', err);
            Swal.fire({
                title: 'Camera Error',
                text: 'Unable to initialize camera. ' + err.message,
                icon: 'error'
            });
            stopCameraScanning();
        }
    }

    function stopCameraScanning() {
        cameraActive = false;
        document.getElementById('cameraModal').style.display = 'none';
        
        const modeStatus = document.getElementById('modeStatus');
        const modeText = document.getElementById('modeText');
        const modeDesc = document.getElementById('modeDesc');
        
        modeStatus.classList.remove('from-purple-50', 'to-pink-50', 'border-purple-200');
        modeStatus.classList.add('from-blue-50', 'to-indigo-50', 'border-blue-200');
        modeText.textContent = 'Keyboard Scanner Mode';
        modeDesc.textContent = 'Ready to scan QR codes using hardware scanner';
        
        if (html5QrcodeScanner) {
            html5QrcodeScanner.stop().catch(err => console.log('Error stopping scanner:', err));
            html5QrcodeScanner = null;
        }
    }

    function validateQr(token) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('{{ route('admin.attendance_forms.validateQr') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                token: token,
                form_id: formId
            })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.valid) {
                Swal.fire({
                    title: 'Invalid QR Code',
                    text: data.message,
                    icon: 'error',
                    confirmButtonColor: '#EF4444'
                });
            } else {
                showAttendanceForm(data.data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Failed to validate QR code',
                icon: 'error'
            });
        });
    }

    function showAttendanceForm(data) {
        Swal.fire({
            title: 'Record Attendance',
            html: `
                <div class="text-left space-y-4 max-h-96 overflow-y-auto">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" id="form_name" value="${data.name}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                            <select id="form_gender" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="male" ${data.gender === 'male' ? 'selected' : ''}>Male</option>
                                <option value="female" ${data.gender === 'female' ? 'selected' : ''}>Female</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                            <input type="text" id="form_contact" value="${data.contact_number}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea id="form_address" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm" rows="2">${data.address}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Signature (Email)</label>
                        <input type="text" id="form_signature" value="${data.signature}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Family Support (Optional)</label>
                        <input type="text" id="form_family_support" placeholder="Enter family support details..." class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Save Record',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280',
            preConfirm: () => {
                const familySupport = document.getElementById('form_family_support').value;
                return {
                    ...data,
                    gender: document.getElementById('form_gender').value,
                    family_support: familySupport || null
                };
            }
        }).then(result => {
            if (result.isConfirmed) {
                saveRecord(result.value);
            }
        });
    }

    function saveRecord(data) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('{{ route('admin.attendance_forms.saveRecord') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                attendance_form_id: formId,
                qr_id: data.qr_id,
                name: data.name,
                gender: data.gender,
                address: data.address,
                signature: data.signature,
                family_support: data.family_support,
                contact_number: data.contact_number
            })
        })
        .then(r => r.json())
        .then(response => {
            if (response.success) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Attendance recorded successfully',
                    icon: 'success',
                    confirmButtonColor: '#3B82F6'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: response.message || 'Failed to save record',
                    icon: 'error'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Failed to save attendance record',
                icon: 'error'
            });
        });
    }

    function deleteRecord(recordId) {
        Swal.fire({
            title: 'Delete Record?',
            text: 'This will remove the attendance record',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280'
        }).then(result => {
            if (result.isConfirmed) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                Swal.fire({
                    title: 'Deleting...',
                    didOpen: (modal) => {
                        Swal.showLoading();
                    },
                    allowOutsideClick: false,
                    allowEscapeKey: false
                });
                
                fetch(`/admin/attendance-records/${recordId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(r => {
                    if (!r.ok) {
                        return r.json().then(e => Promise.reject(e));
                    }
                    return r.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Attendance record has been deleted.',
                            icon: 'success',
                            confirmButtonColor: '#3B82F6'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message || 'Failed to delete record',
                            icon: 'error',
                            confirmButtonColor: '#EF4444'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: error.message || 'Failed to delete record',
                        icon: 'error',
                        confirmButtonColor: '#EF4444'
                    });
                });
            }
        });
    }
</script>

@endsection
