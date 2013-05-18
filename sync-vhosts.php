<?php
$workDir = '/home/san/Work/phpProjects';
$apacheHostsDir = '/etc/apache2/sites-available';
$apacheEnabledHostsDir = '/etc/apache2/sites-enabled';

if ($handle = opendir($workDir)) {
    /* This is the correct way to loop over the directory. */
    while (false !== ($hostDir = readdir($handle))) {
        if(strpos($hostDir, 'local.') !== false) {
            // echo "$hostDir\n";
            
            //check if host exist
            if(!file_exists($apacheHostsDir.'/'.$hostDir)) {
                //add vhost
                $tplCont = file_get_contents(dirname(__FILE__).'/vhosts.tpl');
                $tplCont = str_replace('{{hostName}}', $hostDir, $tplCont);
                $tplCont = str_replace('{{projectsDir}}', $workDir, $tplCont);
                file_put_contents($apacheHostsDir.'/'.$hostDir, $tplCont);
                echo shell_exec('a2ensite '.$hostDir);
                echo 'add '.$hostDir."\n";
            } else if(!file_exists($apacheEnabledHostsDir.'/'.$hostDir)) {
                // echo "$hostDir\n";
                //echo $apacheHostsDir.'/'.$hostDir."\n";
                echo shell_exec('a2ensite '.$hostDir);
                echo 'enable '.$hostDir."\n";
            }
            
            //check hosts
            $needAdd = true;
            $hosts = explode("\n", file_get_contents('/etc/hosts'));
            foreach($hosts as $hostPair) {
                if($hostPair && $hostPair[0] == '#') {
                    continue;
                }
                $tmp = explode(' ', $hostPair);
                if(count($tmp) > 1 && $hostDir == $tmp[1]) {
                    $needAdd = false;
                    break;
                }
            }
            if($needAdd) {
                echo '127.0.0.1 '.$hostDir.' - added in /etc/hosts'."\n";
                $hosts []= '127.0.0.1 '.$hostDir;
                //print_r($hosts);exit;
                file_put_contents('/etc/hosts', implode("\n", $hosts));
            }
        }
    }
    closedir($handle);
    
    echo shell_exec('service apache2 reload');
}
?>