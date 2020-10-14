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
     * Excel输出
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
        $colors=['00a000','53a500','3385FF','00a0d0','0C8080','EFE4B0','8db4e2','00b0f0','0fb746'];//设置总颜色
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
        foreach ($Excels as $key => $Excel) {
            //  ------------- 文件参数 ------------- //
            $cellName = $Excel['cellName'];
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

            for($i=0;$i<$dataNum;$i++){
                for($j=0;$j<$cellNum;$j++){
                    $sheet0->getStyle($cellName[$j].($i+3))->applyFromArray(['alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER]]);
                    $sheet0->setCellValueExplicit($cellName[$j].($i+3),$expTableData[$i][$xlsCell[$j][0]],PHPExcel_Cell_DataType::TYPE_STRING);
                    $sheet0->getStyle($cellName[$j].($i+3))->getNumberFormat()->setFormatCode("@");
                }
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

            $sheet0->getStyle('A2:'.$cellName[$cellNum-1].($i+2))->applyFromArray($styleArray);
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
