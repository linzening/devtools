<?php
namespace linzening\devtools\tests;
use think\Db;
class Xlsx
{
    public function index()
    {
    	//文件名称
		$Excel['fileName']="PHPExcel示例".date('Y年m月d日-His',time());//or $xlsTitle
		$Excel['cellName']=['A','B','C','D'];
		$Excel['H'] = ['A'=>22,'B'=>26,'C'=>30,'D'=>30];//横向水平宽度
		$Excel['V'] = ['1'=>40,'2'=>26];//纵向垂直高度
		$Excel['sheetTitle']="PHPExcel示例";//大标题，自定义
		$Excel['xlsCell']=$this->head();
		$data=$this->data();
		model("excelTools")->excelPut($Excel,$data);
    }
    public function head(){
		return [['autoid','序号'],
			['school','学校'],
			['addr','所在地'],
			['type','类型']
		];
	}
	public function data(){
		return [
			['autoid'=>'1','school'=>'云南大学','addr'=>'云南省','type'=>'综合'],
			['autoid'=>'2','school'=>'云南财经大学','addr'=>'云南省','type'=>'财经'],
			['autoid'=>'3','school'=>'云南民族大学','addr'=>'云南省','type'=>'综合'],
			['autoid'=>'4','school'=>'云南师范大学','addr'=>'云南省','type'=>'师范'],
			['autoid'=>'5','school'=>'云南旅游大学','addr'=>'云南省','type'=>'综合'],
			['autoid'=>'6','school'=>'贵州大学','addr'=>'贵州省','type'=>'综合'],
			['autoid'=>'7','school'=>'贵州财经大学','addr'=>'贵州省','type'=>'财经'],
			['autoid'=>'7','school'=>'贵州师范大学','addr'=>'贵州省','type'=>'师范']
		];
	}

    // 合并单元格的情况，用于做对阵表
    public static function exput(){
        // $sql = 'SELECT *,p.id partid,m.id major_id,m.name major_name FROM `b_major` m LEFT JOIN b_part p ON m.partid = p.id';
        $sql = 'SELECT m.*,p.id part_id,p.name part_name
        FROM `b_admin` m LEFT JOIN b_part p ON m.part = p.id order by part_id asc';
        $list = Db::query($sql);

        $parts = array_group_by($list,'part_id');

        $list0 = [];
        foreach ($parts as $key => $value) {
            $item0['part_id'] = $value[0]['part_id'];
            $item0['part_name'] = $value[0]['part_name'];
            $item0['items'] = [];
            foreach ($value as $key1 => $value1) {
                $item0['items'][] = [
                    'realname' => $value1['realname'],
                    'telephone' => $value1['telephone'],
                    'account' => $value1['account'],
                ];
            }
            $list0[] = $item0;
        }
        
        $fileName = "高尔夫球对阵表";
		$Excel['fileName'] = $fileName.date('Y年m月d日-His',time());//or $xlsTitle
		$Excel['cellName'] = 5;
		$Excel['bArray'] = [2,3];
		// $Excel['H'] = ['A'=>22,'B'=>20,'C'=>30,'D'=>40,'E'=>30];//横向水平宽度
		$Excel['H'] = [22,20,30,40,30];//横向水平宽度
		$Excel['sheetTitle'] = $fileName;//大标题，自定义
		// $Excel['sheetName'] = '名单列表';//表格名称
		$Excel['xlsCell']=[
            ['part_id','学院ID'],
            ['part_name','学院名称'],
            ['realname','姓名'],
            ['telephone','手机'],
            ['account','账号']
        ];
        $Excel['expTableData'] = $list0;
        $Excels[] = $Excel;

        // $Excel['fileName']="PHPExcel示例".date('Y年m月d日-His',time());//or $xlsTitle
		$Excel['cellName']= 5;
		$Excel['H'] = [22,26,30,22,20];//横向水平宽度
		// $Excel['V'] = ['1'=>40,'2'=>26];//纵向垂直高度
		$Excel['sheetTitle']="对阵表";//大标题，自定义
		// $Excel['sheetName']="名单列表2";//大标题，自定义
		$Excel['xlsCell']= [
            ['part_id','学院ID'],
            ['part_name','学院名称'],
            ['realname','姓名'],
            ['telephone','手机'],
            ['account','账号']
        ];
        $Excel['expTableData'] = $list;
        $Excels[] = $Excel;

        \linzening\devtools\Spread::excelPuts($Excels);
    }
}
