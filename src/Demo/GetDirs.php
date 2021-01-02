<?php
namespace linzening\devtools\Demo;

/**
 * 获取目录下的所有目录和文件
 */
class GetDirs
{
    // 返回array
    public function index(){
        return $this->get_filenamesbydir('.');
    }

    // 递归获取目录下的文件
    public function get_allfiles($path, &$files)
    {
        if (is_dir($path)) {
            $dp = dir($path);
            while ($file = $dp->read()) {
                if ($file !== "." && $file !== "..") {
                    $this->get_allfiles($path . "/" . $file, $files);
                }
            }
            $dp->close();
        }
        if (is_file($path)) {
            $files[] = $path;
        }
    }

    // 执行开始，返回数组
    public function get_filenamesbydir($dir)
    {
        $files = array();
        $this->get_allfiles($dir, $files);
        return $files;
    }
}
