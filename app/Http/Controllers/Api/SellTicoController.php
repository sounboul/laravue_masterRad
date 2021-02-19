<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SellTicoController extends Controller
{

	public function index()

    {

    	$response = Http::withBasicAuth('phoenix2208@gmail.com', 'sasazivkovic1')->get('http://dev.tico.rs/api/v1/articles');
    	//$response = Http::withBasicAuth('phoenix2208@gmail.com', 'sasazivkovic1')->get('http://dev.tico.rs/files/sync/A_YStock.xls.xml');
    		//dd($response);
	        //$items = simplexml_load_file($response);
	        //dd($items);
        /*$xmlString = file_get_contents(public_path('sample.xml'));

        $xmlObject = simplexml_load_string($xmlString);

                   

        $json = json_encode($xmlObject);

        $phpArray = json_decode($json, true); 

   

        dd($phpArray);*/
  
        return $response->json();





    	//$jsonData = $response->json();
    	//$jsonData = $response->getBody();

      	

     	//dd($jsonData);

    }


    /*
    private static function array_map_assoc(callable $f, array $a) {
        return array_column(array_map($f, array_keys($a), $a), 1, 0);
    }

	private static function readArticles($mappedManufacturers){
	        $sqlData = [];
	        $id = 0;
	        $items = simplexml_load_file("files/sync/A_YStock.xls.xml");
	        foreach($items as $item){
	            $column = $item->column;
	            $id++;
	            $sqlData[] = "(".strval($id).",'".$column[3]."','".$column[5]."',".($column[9]=='Y'?'0':'1').",".($column[7]=='Y'?'1':'0').",".($column[8]=='Y'?'1':'0').",".$column[18].",".$column[19].",".$column[20].",".$column[21].",".$column[10].",".$column[11].",".$column[12].",".$column[13].",".$column[14].",".$column[15].",".$column[16].",".(($column[23] > 0 && isset($mappedManufacturers[intval($column[23])])) ? $mappedManufacturers[intval($column[23])] : 'NULL').",".(!empty($column[17])?"'".$column[17]."'":'NULL').",".$column[2].")";
			}
		return (object) array('sql'=>implode(',',$sqlData));
}


    private static function loadArticles($sqlData){
		DB::statement("DROP TEMPORARY TABLE IF EXISTS article_temp");
		DB::statement("CREATE TEMPORARY TABLE IF NOT EXISTS article_temp (id int(10), part_no varchar(500), main_description 	varchar(500), active tinyint(4), kit tinyint(4), seal tinyint(4), price double(20,2), price2 double(20,2), price3 double(20,2), price4 double(20,2), size double(10,2), size2 double(10,2), size3 double(10,2), size_a double(10,2), size_b double(10,2), size_c double(10,2), size_h double(10,2), anufacturer_id int(11), created_date timestamp NULL DEFAULT NULL, id_is int(10))");
        DB::statement("INSERT INTO article_temp (id, part_no, main_description, active, kit, seal, price, price2, price3, price4,
			size, size2, size3, size_a, size_b, size_c, size_h, manufacturer_id, created_date, id_is) VALUES ".$sqlData);
    }

    private static function syncArticles(){
        DB::statement("UPDATE article a INNER JOIN article_temp at ON
			a.id_is=at.id_is SET a.part_no=at.part_no,
			a.main_description=at.main_description, a.active=at.active,
			a.kit=at.kit, a.seal=at.seal, a.price=at.price, a.price2=at.price2,
			a.price3=at.price3, a.price4=at.price4, a.size=at.size,
			a.size2=at.size2, a.size3=at.size3, a.size_a=at.size_a,
			a.size_b=at.size_b, a.size_c=at.size_c, a.size_h=at.size_h,
			a.manufacturer_id=at.manufacturer_id, a.created_date=at.created_date");
        DB::statement("INSERT INTO article (part_no, main_description, active, kit, seal, price, price2, price3, price4, size, size2, size3, size_a, size_b, size_c, size_h, manufacturer_id, created_date, id_is)
			SELECT part_no, main_description, active, kit, seal, price, price2, price3, price4, size, size2, size3, size_a, size_b, size_c, size_h, manufacturer_id, created_date, id_is FROM article_temp at WHERE at.id_is NOT IN (SELECT id_is FROM article WHERE id_is IS NOT NULL)");

         DB::statement("DROP TEMPORARY TABLE IF EXISTS article_temp");
    }


         $mappedManufacturers = self::array_map_assoc(function($key,$value){
             return [$value->id_is, $value->id];
         },DB::table('manufacturer')->get());


         $articlesData = self::readArticles($mappedManufacturers);
         self::loadArticles($articlesData->sql);
         self::syncArticles();*/


}
