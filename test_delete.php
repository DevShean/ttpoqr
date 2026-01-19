<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\Log;

// Get all attendance records
$records = AttendanceRecord::limit(3)->get();

echo "Found " . count($records) . " records\n";

if ($records->count() > 0) {
    $record = $records->first();
    echo "Testing delete on record ID: " . $record->id . " Name: " . $record->name . "\n";
    
    $before = AttendanceRecord::where('id', $record->id)->exists();
    echo "Before delete - Record exists in DB: " . ($before ? 'YES' : 'NO') . "\n";
    
    $result = $record->delete();
    echo "Delete method returned: " . ($result ? 'TRUE' : 'FALSE') . "\n";
    
    $after = AttendanceRecord::where('id', $record->id)->exists();
    echo "After delete - Record exists in DB: " . ($after ? 'YES' : 'NO') . "\n";
    
    if ($before && $after) {
        echo "\n*** PROBLEM: Record was NOT deleted from database ***\n";
    } elseif ($before && !$after) {
        echo "\n*** OK: Record was successfully deleted ***\n";
    }
} else {
    echo "No records found to test with\n";
}
?>
