<?php 


$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                $domainName = $_SERVER['HTTP_HOST'];            
                $folder_name = str_replace(strtolower($_SERVER['DOCUMENT_ROOT']), '', str_replace('\\','/',strtolower(FCPATH)));
                $server_path = $protocol.$domainName.$folder_name;
                $config['base_url'] = $server_path;

$rule = $folder_name.'index.php [L]';
$data = <<<EOF
RewriteEngine On
RewriteBase $folder_name
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . $rule
EOF;
file_put_contents('.htaccess', $data);



 ?>