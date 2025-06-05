<?php
// Temporary PHP info page for configuration verification
// Remove this file after verification for security

echo "<h2>PHP Configuration Check for EquipNow</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Setting</th><th>Value</th></tr>";

$settings = [
    'PHP Version' => phpversion(),
    'memory_limit' => ini_get('memory_limit'),
    'upload_max_filesize' => ini_get('upload_max_filesize'),
    'post_max_size' => ini_get('post_max_size'),
    'max_execution_time' => ini_get('max_execution_time'),
    'max_input_time' => ini_get('max_input_time'),
    'max_file_uploads' => ini_get('max_file_uploads'),
    'max_input_vars' => ini_get('max_input_vars'),
    'default_socket_timeout' => ini_get('default_socket_timeout'),
];

foreach ($settings as $name => $value) {
    echo "<tr><td><strong>$name</strong></td><td>$value</td></tr>";
}

echo "</table>";
echo "<p><em>Generated on: " . date('Y-m-d H:i:s') . "</em></p>";
?>
