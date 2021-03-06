<?php
/*
 * 这个是PhpSpreadsheet的工具
 */
namespace linzening\devtools;
// --- 生成Excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment as PHPExcel_Style_Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataType as PHPExcel_Cell_DataType;
use PhpOffice\PhpSpreadsheet\Style\Fill as PHPExcel_Style_Fill;
use PhpOffice\PhpSpreadsheet\Style\Border as Border;
// --- 读取Excel
use PhpOffice\PhpSpreadsheet\IOFactory;
class Spread
{
    /**
     * [name] 样例
     * @access public
     * @return null
     */
    public static function putdemo(){
		$fileName = "test";
		$Excel['fileName']=$fileName.date('Y年m月d日-His',time());//or $xlsTitle
		$Excel['cellName']=['A','B','C'];
		$Excel['H'] = ['A'=>22,'B'=>12,'C'=>30];//横向水平宽度
		$Excel['V'] = ['1'=>40,'2'=>26];//纵向垂直高度
		$Excel['sheetTitle']=$fileName;//大标题，自定义
		$Excel['xlsCell']=[['majorid','专业号'],['majorshort','专业简称'],
		['majorname','专业名称']];
		$expTableData = db("major")->select();
        $this::excelPut($Excel,$expTableData);

        // +多个表格的excelPuts
        $fileName = "多表格导出";
		$Excel['fileName']=$fileName.date('Y年m月d日-His',time());//or $xlsTitle
		$Excel['cellName']=['A','B','C'];
		$Excel['H'] = ['A'=>22,'B'=>12,'C'=>30];//横向水平宽度
		$Excel['V'] = ['1'=>40,'2'=>26];//纵向垂直高度
		$Excel['sheetTitle']=$fileName;//大标题，自定义
		$Excel['xlsCell']=[
            ['id','专业号'],
            ['short','专业简称'],
            ['name','专业名称']
        ];
        $expTableData = db("major")->select();
        $Excel['expTableData'] = $expTableData;
        $Excels[] = $Excel;

        $fileName = "扣除的AAAA";
		$Excel['fileName']=$fileName.date('Y年m月d日-His',time());//or $xlsTitle
		$Excel['cellName']=['A','B','C'];
		$Excel['H'] = ['A'=>22,'B'=>40,'C'=>30];//横向水平宽度
		$Excel['V'] = ['1'=>40,'2'=>26];//纵向垂直高度
		$Excel['sheetTitle'] = $fileName;//大标题，自定义
		$Excel['sheetName'] = '扣除';//表格名称
		$Excel['xlsCell']=[
            ['studentid','学生号'],
            ['reason','原因'],
            ['num','数量']
        ];
        $expTableData = db("deduction")->select();
        $Excel['expTableData'] = $expTableData;
        $Excels[] = $Excel;
		\linzening\devtools\Spread::excelPuts($Excels);
	}

    /**
     * Excel输出：即将废弃的方法
     * @access public
     * @param  array     $Excel Excel相关配置信息
     * @param  array     $expTableData 表格里面的数据
     * @return file
     */
    public static function excelPut($Excel,$expTableData){
        // $Excel['sheetTitle']=iconv('utf-8', 'gb2312',$Excel['sheetTitle']);
        //  ------------- 文件参数 -------------
        $cellName = $Excel['cellName'];
        $xlsCell = $Excel['xlsCell'];
        $cellNum = count($xlsCell);//计算总列数
        $dataNum = count($expTableData);//计算数据总行数
        $spreadsheet = new Spreadsheet();
        $sheet0 = $spreadsheet->getActiveSheet();
        $sheet0->setTitle("Sheet1");
        //设置表格标题A1
        $sheet0->mergeCells('A1:'.$cellName[$cellNum-1].'1');//表头合并单元格

        // ------------- 表头 -------------
        // $sheet0->setCellValue('A1',"测试表头");
        $sheet0->setCellValue('A1',$Excel['sheetTitle']);

        $sheet0->getStyle('A1')->getFont()->setSize(20);
        $sheet0->getStyle('A1')->getFont()->setName('微软雅黑');
        //设置行高和列宽
        // ------------- 横向水平宽度 -------------
        if(isset($Excel['H'])){
            foreach ($Excel['H'] as $key => $value) {
                $sheet0->getColumnDimension($key)->setWidth($value);
            }
        }

        // ------------- 纵向垂直高度 -------------
        if(isset($Excel['V'])){
            foreach ($Excel['V'] as $key => $value) {
                $sheet0->getRowDimension($key)->setRowHeight($value);
            }
        }

        // ------------- 第二行：表头要加粗和居中，加入颜色 -------------
        $sheet0->getStyle('A1')
        ->applyFromArray(['font' => ['bold' => false],'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER]]);
        $setcolor = $sheet0->getStyle("A2:".$cellName[$cellNum-1]."2")->getFill();
        $setcolor->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $colors=['00a000','53a500','3385FF','00a0d0','0C8080','EFE4B0','00b0f0','0fb746','FF6A6A','FFC1C1','EE9A49','66CDAA','CDC673','CD6839'];//设置总颜色
        $selectcolor=$colors[mt_rand(0,count($colors)-1)];//获取随机颜色
        $setcolor->getStartColor()->setRGB($selectcolor);

        // ------------- 根据表格数据设置列名称 -------------

        for($i=0;$i<$cellNum;$i++){
            $sheet0->setCellValue($cellName[$i].'2', $xlsCell[$i][1])
            ->getStyle($cellName[$i].'2')
            ->applyFromArray(['font' => ['bold' => true],'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER]]);
        }

        // ------------- 渲染表中数据内容部分 -------------

        for($i=0;$i<$dataNum;$i++){
            for($j=0;$j<$cellNum;$j++){
                $sheet0->getStyle($cellName[$j].($i+3))->applyFromArray(['alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER]]);
                $sheet0->setCellValueExplicit($cellName[$j].($i+3),$expTableData[$i][$xlsCell[$j][0]],PHPExcel_Cell_DataType::TYPE_STRING);
                $sheet0->getStyle($cellName[$j].($i+3))->getNumberFormat()->setFormatCode("@");
            }
        }

        // ------------- 设置边框 -------------
        // $sheet0->getStyle('A2:'.$cellName[$cellNum-1].($i+2))->applyFromArray(['borders' => ['allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN]]]);

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF505050'],
                ],
            ],
        ];

        $sheet0->getStyle('A2:'.$cellName[$cellNum-1].($i+2))->applyFromArray($styleArray);

        //$sheet0->setCellValue("A".($dataNum+10)," ");//多设置一些行

        // ------------- 输出 -------------
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');//告诉浏览器输出07Excel文件
        //header('Content-Type:application/vnd.ms-excel');//告诉浏览器将要输出Excel03版本文件
        header("Content-Disposition: attachment;filename=".$Excel['fileName'].".xlsx");//告诉浏览器输出浏览器名称
        header('Cache-Control: max-age=0');//禁止缓存
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Excel输出多个表格
     * @access public
     * @param  array     $Excel Excel相关配置信息
     * @param  array     $expTableData 表格里面的数据
     * @return file
     */
    public static function excelPuts($Excels){
        $spreadsheet = new Spreadsheet();
        foreach ($Excels as $key_number => $Excel) {
            //  ------------- 文件参数 ------------- //
            // $cellName0s = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
            // $cellName0s = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
            //             'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS'];
            $cellName0s = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
                'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',
                'BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ',
                'CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ',
                'DA','DB','DC','DD','DE','DF','DG','DH','DI','DJ','DK','DL','DM','DN','DO','DP','DQ','DR','DS','DT','DU','DV','DW','DX','DY','DZ'];
            if( ! isset($Excel['cellName']) ){
                $cellName = array_splice($cellName0s,0,count($Excel['xlsCell']));
            }elseif(gettype($Excel['cellName']) == 'integer'){
                $cellName = array_splice($cellName0s,0,$Excel['cellName']);
            }elseif(gettype($Excel['cellName']) == 'array'){
                $cellName = $Excel['cellName'];
            }

            $xlsCell = $Excel['xlsCell']; //列名称
            $cellNum = count($xlsCell);//计算总列数
            $expTableData = $Excel['expTableData']; //表格内数据
            $dataNum = count($expTableData);//计算数据总行数
            if($key_number === 0)
                $sheet0 = $spreadsheet->getActiveSheet(); //默认的第一个表格
            else
                $sheet0 = $spreadsheet->createSheet();
            $sheet0->setTitle( $Excel['sheetName'] ?? "Sheet".($key_number+1) );
            //设置表格标题A1
            $sheet0->mergeCells('A1:'.$cellName[$cellNum-1].'1');//表头合并单元格

            // ------------- 表头 ------------- //
            $sheet0->setCellValue('A1',$Excel['sheetTitle']);

            $sheet0->getStyle('A1')->getFont()->setSize(20);
            $sheet0->getStyle('A1')->getFont()->setName('微软雅黑');
            //设置行高和列宽
            // ------------- 横向水平宽度 ------------- //
            // if(isset($Excel['H'])){
            //     foreach ($Excel['H'] as $key => $value) {
            //         $key0 = $key;
            //         if( gettype($key0) == 'integer' ){
            //             $key0 = $cellName0s[$key0];
            //         }
            //         $sheet0->getColumnDimension($key0)->setWidth($value);
            //     }
            // }

            // ------------- 自动生成单元格宽度 ------------- //
            if( $Excel['H'] === 1 || $Excel['H'] == 'auto' ) {
                # 自动匹配宽度
                $h = [];
                foreach ($expTableData as $key => $value) {
                    if( $key < 60 ){
                        foreach ($Excel['xlsCell'] as $key0 => $value0) {
                            $len = strlen($value[$value0[0]]) + 5;
                            $len = $len < 8 ? 8 : $len;
                            $len = $len > 22 ? $len * ( 1 - $len / 250 ) : $len;
                            $h[$key0] = $h[$key0] ?? 0;
                            if( $h[$key0] < $len ){
                                $h[$key0] = $len;
                            }
                        }
                    }
                }
                $Excel['H'] = $h;
                // halt($Excel['H']);
            }

            if(isset($Excel['H'])){
                foreach ($Excel['H'] as $key => $value) {
                    if( gettype($key) == 'integer' ){
                        $key0 = $cellName[$key];
                    }else{
                        $key0 = $key;
                    }
                    $sheet0->getColumnDimension($key0)->setWidth($value);
                }
            }

            // ------------- 纵向垂直高度：行高 ------------- //
            if( ! isset($Excel['V']) ){
                $Excel['V'] = ['1'=>40,'2'=>26];
            }

            foreach ($Excel['V'] as $key => $value) {
                $sheet0->getRowDimension($key)->setRowHeight($value);
            }

            // ------------- 第二行：表头要加粗和居中，加入颜色 ------------- //
            $sheet0->getStyle('A1')
            ->applyFromArray(['font' => ['bold' => false],'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER]]);
            $setcolor = $sheet0->getStyle("A2:".$cellName[$cellNum-1]."2")->getFill();
            $setcolor->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $colors=['00a000','53a500','3385FF','00a0d0','0C8080','EFE4B0','8db4e2','00b0f0','0fb746'];//设置总颜色
            $selectcolor=$colors[mt_rand(0,count($colors)-1)];//获取随机颜色
            $setcolor->getStartColor()->setRGB($selectcolor);

            // ------------- 根据表格数据设置列名称 ------------- //

            // for($i=0;$i<$cellNum;$i++){
            //     $sheet0->setCellValue($cellName[$i].'2', $xlsCell[$i][1])
            //     ->getStyle($cellName[$i].'2')
            //     ->applyFromArray(['font' => ['bold' => true],'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER]]);
            // }

            foreach ($xlsCell as $key => $value) {
                $sheet0->setCellValue($cellName[$key].'2', $xlsCell[$key][1])
                ->getStyle($cellName[$key].'2')
                ->applyFromArray(['font' => ['bold' => true],'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER]]);
            }

            $row0 = 3; //从第三行开始渲染数据

            // ------------- 渲染表中数据内容部分：合并渲染和普通渲染 ------------- //
            if( isset($expTableData[0]['items']) && gettype($expTableData[0]['items']) == 'array'){
                foreach ($expTableData as $key7 => $value7) {
                    // $value7为每一大单条数据
                    $atom = count($value7['items']); //小单元格的行数
                    
                    $temp_i = 0; //父级元素个数
                    foreach ($value7 as $key50 => $value50) {
                        if($key50 != 'items'){
                            // 处理合并单元格
                            $sheet0->mergeCells($cellName[$temp_i].$row0.':'.$cellName[$temp_i].($row0 + $atom - 1));

                            // 填充合并单元格数据
                            $sheet0->getStyle($cellName[$temp_i].($row0))->applyFromArray(['alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER]]);
                            $sheet0->setCellValueExplicit($cellName[$temp_i].($row0),$value7[$key50],PHPExcel_Cell_DataType::TYPE_STRING);
                            $sheet0->getStyle($cellName[$temp_i].($row0))->getNumberFormat()->setFormatCode("@");

                            $temp_i++;
                        }
                    }
                    
                    foreach ($value7['items'] as $key9 => $value9) {
                        // $value9是一行的数据
                        $temp_j = 0; //子级元素字段个数
                        foreach ($value9 as $key90 => $value90) {
                            $s = $cellName[$temp_i + $temp_j].($row0 + $key9);
                            $sheet0->getStyle($s)->applyFromArray(['alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER]]);
                            $sheet0->setCellValueExplicit($s,$value9[$key90],PHPExcel_Cell_DataType::TYPE_STRING);

                            $temp_j++;
                        }
                    }

                    $row0 += $atom;
                }
                $row0--;
            }else{
                // ------------- 渲染表中数据内容部分：普通渲染 ------------- //
                for($i=0;$i<$dataNum;$i++){
                    for($j=0;$j<$cellNum;$j++){
                        $sheet0->getStyle($cellName[$j].($i+3))->applyFromArray(['alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER]]);
                        $sheet0->setCellValueExplicit($cellName[$j].($i+3),$expTableData[$i][$xlsCell[$j][0]],PHPExcel_Cell_DataType::TYPE_STRING);
                        $sheet0->getStyle($cellName[$j].($i+3))->getNumberFormat()->setFormatCode("@");
                    }
                }
                $row0 += $i - 1;
            }

            // ------------- 设置边框 ------------- //

            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF505050'],
                    ],
                ],
            ];

            $sheet0->getStyle('A2:'.$cellName[$cellNum-1] . $row0 )->applyFromArray($styleArray);
        }
        $Excel = $Excels[0];

        // 命令行下，导出到服务器
        if( isset($Excel['local']) && $Excel['local'] === true){
            //导出到服务器
            ob_start();
            $objWriter = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $objWriter->save('./'.$Excel['fileName'].'.xlsx');
            /* 释放内存 */
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
            ob_end_flush();
            exit('结束时间'.date('Y-m-d H:i:s')."\n");
        }

        // ------------- 输出 -------------
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');//告诉浏览器输出07Excel文件
        header("Content-Disposition: attachment;filename=".$Excel['fileName'].".xlsx");//告诉浏览器输出浏览器名称
        header('Cache-Control: max-age=0');//禁止缓存
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Excel合并单元格的情况：即将废弃的方法
     * @access public
     * @param  array     $Excel Excel相关配置信息
     * @param  array     $expTableData 表格里面的数据
     * @return file
     */
    public static function mergePuts($Excels){
        $spreadsheet = new Spreadsheet();
        foreach ($Excels as $key => $Excel) {
            //  ------------- 文件参数 ------------- //
            if(gettype($Excel['cellName']) == 'integer'){
                $cellName0s = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
                $cellName = array_splice($cellName0s,0,$Excel['cellName']);
            }elseif(gettype($Excel['cellName']) == 'array'){
                $cellName = $Excel['cellName'];
            }
            $xlsCell = $Excel['xlsCell'];
            $cellNum = count($xlsCell);//计算总列数
            $expTableData = $Excel['expTableData'];
            $dataNum = count($expTableData);//计算数据总行数
            if($key === 0)
            $sheet0 = $spreadsheet->getActiveSheet(); //默认的第一个表格
            else
            $sheet0 = $spreadsheet->createSheet();
            $sheet0->setTitle( $Excel['sheetName'] ?? "Sheet".($key+1) );
            //设置表格标题A1
            $sheet0->mergeCells('A1:'.$cellName[$cellNum-1].'1');//表头合并单元格

            // ------------- 表头 ------------- //
            $sheet0->setCellValue('A1',$Excel['sheetTitle']);

            $sheet0->getStyle('A1')->getFont()->setSize(20);
            $sheet0->getStyle('A1')->getFont()->setName('微软雅黑');
            //设置行高和列宽
            // ------------- 横向水平宽度 ------------- //
            if(isset($Excel['H'])){
                foreach ($Excel['H'] as $key => $value) {
                    $sheet0->getColumnDimension($key)->setWidth($value);
                }
            }

            // ------------- 纵向垂直高度 ------------- //
            if(isset($Excel['V'])){
                foreach ($Excel['V'] as $key => $value) {
                    $sheet0->getRowDimension($key)->setRowHeight($value);
                }
            }

            // ------------- 第二行：表头要加粗和居中，加入颜色 ------------- //
            $sheet0->getStyle('A1')
            ->applyFromArray(['font' => ['bold' => false],'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER]]);
            $setcolor = $sheet0->getStyle("A2:".$cellName[$cellNum-1]."2")->getFill();
            $setcolor->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $colors=['00a000','53a500','3385FF','00a0d0','0C8080','EFE4B0','8db4e2','00b0f0','0fb746'];//设置总颜色
            $selectcolor=$colors[mt_rand(0,count($colors)-1)];//获取随机颜色
            $setcolor->getStartColor()->setRGB($selectcolor);

            // ------------- 根据表格数据设置列名称 ------------- //

            for($i=0;$i<$cellNum;$i++){
                $sheet0->setCellValue($cellName[$i].'2', $xlsCell[$i][1])
                ->getStyle($cellName[$i].'2')
                ->applyFromArray(['font' => ['bold' => true],'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER]]);
            }

            // ------------- 渲染表中数据内容部分 ------------- //
            $row0 = 3; //从第三行开始渲染数据
            // 合并表格的情况
            if( isset($expTableData[0]['items']) && gettype($expTableData[0]['items']) == 'array'){
                foreach ($expTableData as $key7 => $value7) {
                    // $value7为每一大单条数据
                    $atom = count($value7['items']); //小单元格的行数
                    
                    $temp_i = 0; //父级元素个数
                    foreach ($value7 as $key50 => $value50) {
                        if($key50 != 'items'){
                            // 处理合并单元格
                            $sheet0->mergeCells($cellName[$temp_i].$row0.':'.$cellName[$temp_i].($row0 + $atom - 1));

                            // 填充合并单元格数据
                            $sheet0->getStyle($cellName[$temp_i].($row0))->applyFromArray(['alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER]]);
                            $sheet0->setCellValueExplicit($cellName[$temp_i].($row0),$value7[$key50],PHPExcel_Cell_DataType::TYPE_STRING);
                            $sheet0->getStyle($cellName[$temp_i].($row0))->getNumberFormat()->setFormatCode("@");

                            $temp_i++;
                        }
                    }
                    
                    foreach ($value7['items'] as $key9 => $value9) {
                        // $value9是一行的数据
                        $temp_j = 0; //子级元素字段个数
                        foreach ($value9 as $key90 => $value90) {
                            $s = $cellName[$temp_i + $temp_j].($row0 + $key9);
                            $sheet0->getStyle($s)->applyFromArray(['alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER]]);
                            $sheet0->setCellValueExplicit($s,$value9[$key90],PHPExcel_Cell_DataType::TYPE_STRING);

                            $temp_j++;
                        }
                    }

                    $row0 += $atom;
                }
            }

            $row0--;

            // ------------- 设置边框 ------------- //

            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF505050'],
                    ],
                ],
            ];

            $sheet0->getStyle('A2:'.$cellName[$cellNum-1].($row0))->applyFromArray($styleArray);
        }
        $Excel = $Excels[0];
        // ------------- 输出 -------------
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');//告诉浏览器输出07Excel文件
        header("Content-Disposition: attachment;filename=".$Excel['fileName'].".xlsx");//告诉浏览器输出浏览器名称
        header('Cache-Control: max-age=0');//禁止缓存
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Excel读取
     * @access public
     * @param  string  $filePath Excel文件存放的路径
     * @return array
     */
	public static function excelReader($uploadfile){
        $inputFileType = IOFactory::identify($uploadfile);
        $excelReader   = IOFactory::createReader($inputFileType); //Xlsx
        $PHPExcel      = $excelReader->load($uploadfile); // 载入excel文件
        $sheet         = $PHPExcel->getSheet(0); // 读取第一個工作表
        $sheetdata = $sheet->toArray();
        return $sheetdata; // --- 直接返回数组数据
	}
}
