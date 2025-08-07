if (!empty($dbname)) {
 $cfg['LoginCookieValidity'] = 3600 * 9; // 9 horas
 /* Authentication type */
 $cfg['Servers'][$i]['auth_type'] = 'cookie';
 /* Server parameters */
 if (empty($dbserver)) $dbserver = 'localhost';
 $cfg['Servers'][$i]['host'] = $dbserver;

 if (!empty($dbport)) {
 $cfg['Servers'][$i]['connect_type'] = 'tcp';
 $cfg['Servers'][$i]['port'] = $dbport;
 }