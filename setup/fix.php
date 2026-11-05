<?php

## Function to set file permissions to 0644 and folder permissions to 0755,
## except for the application, mcache, hcache, and setup folders.

function AllDirChmod( $dir = "./", $dirModes = 0755, $fileModes = 0644 ){
   $writable = array( 'application', 'mcache', 'hcache', 'setup' );
   $root = rtrim( $dir, DIRECTORY_SEPARATOR );
   $d = new RecursiveDirectoryIterator( $dir, FilesystemIterator::SKIP_DOTS );
   foreach( new RecursiveIteratorIterator( $d, RecursiveIteratorIterator::SELF_FIRST ) as $path ){
      if( $path->isDir() ) {
         $mode = $dirModes;
         $rel = ltrim( substr( $path->getPathname(), strlen( $root ) ), '/\\' );
         $top = preg_split( '#[/\\\\]#', $rel )[0];
         if( in_array( $top, $writable, true ) ) {
            $mode = 0777;
         }
         chmod( $path->getPathname(), $mode );
      }
      else if( is_file( $path->getPathname() ) ) chmod( $path->getPathname(), $fileModes );
  }
   foreach( $writable as $w ) {
      if( is_dir( $root . '/' . $w ) ) chmod( $root . '/' . $w, 0777 );
   }
}

## Function to clean out the contents of specified directory

function cleandir($dir) {

    if ($handle = opendir($dir)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..' && is_file($dir.'/'.$file)) {
                if (unlink($dir.'/'.$file)) { }
                else { echo $dir . '/' . $file . ' (file) NOT deleted!<br />'; }
            }
            else if ($file != '.' && $file != '..' && is_dir($dir.'/'.$file)) {
                cleandir($dir.'/'.$file);
                if (rmdir($dir.'/'.$file)) { }
                else { echo $dir . '/' . $file . ' (directory) NOT deleted!<br />'; }
            }
        }
        closedir($handle);
    }

}

function isDirEmpty($dir){
     return (($files = @scandir($dir)) && count($files) <= 2);
}

echo "----------------------- CLEANUP START -------------------------<br/>";
$start = (float) array_sum(explode(' ',microtime()));
echo "<br/>*************** SETTING PERMISSIONS ***************<br/>";
echo "Setting all folder permissions to 755, except application, mcache, hcache, and setup to 777<br/>";
echo "Setting all file permissions to 644<br/>";
AllDirChmod( "." );

$end = (float) array_sum(explode(' ',microtime()));
echo "<br/>------------------- CLEANUP COMPLETED in:". sprintf("%.4f", ($end-$start))." seconds ------------------<br/>";
?>