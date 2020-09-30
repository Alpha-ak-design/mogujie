<?php
/**
 * Created by PhpStorm.
 * User: 86131
 * Date: 2020/9/1
 * Time: 19:57
 */

namespace app\index\controller;


use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use think\Controller;
use think\Session;

class TestEla extends Controller
{
    private $client;
    public function __construct()
    {
        $params = [
            '127.0.0.1:9200',
        ];
        $this->client = ClientBuilder::create()->setHosts($params)->build();
    }

    // 创建索引
    public function index() { // 只能创建一次

//        $r = $this->create_index();  //1.创建索引
//        echo "<pre>";
//        print_r($r);
//        exit;
//
//        $r = $this->create_mappings(); //2.创建文档模板
//        echo "<pre>";
//        print_r($r);
//        exit;

//        $r = $this->get_mapping();
//
//        $r = $this->delete_index();

        $docs = [];
        $docs[] = ['id'=>1,'name'=>'小明','profile'=>'我做的ui界面强无敌。','age'=>23];
        $docs[] = ['id'=>2,'name'=>'小张','profile'=>'我的php代码无懈可击。','age'=>24];
        $docs[] = ['id'=>3,'name'=>'小王','profile'=>'C的生活，快乐每一天。','age'=>29];
        $docs[] = ['id'=>4,'name'=>'小赵','profile'=>'就没有我做不出的前端页面。','age'=>26];
        $docs[] = ['id'=>5,'name'=>'小吴','profile'=>'php是最好的语言。','job'=>21];
        $docs[] = ['id'=>6,'name'=>'小翁','profile'=>'别烦我，我正在敲bug呢！','age'=>25];
        $docs[] = ['id'=>7,'name'=>'小杨','profile'=>'为所欲为，不行就删库跑路','age'=>27];

//        foreach ($docs as $k => $v) {
//            $r = $this->add_doc($v['id'],$v);   //3.添加文档
//        }
//        echo "<pre>";
//        print_r($r);
//        exit;
        $r = $this->search_doc("删库 别烦我");  //4.搜索结果
        echo "<pre>";
        print_r($r);
        exit;
    }

    // 创建索引test_ik
    public function create_index($index_name = '') { // 只能创建一次
        $params = [
            'index' => $index_name,
            'body' => [
                'settings' => [
                    'number_of_shards' => 5,
                    'number_of_replicas' => 0
                ]
            ]
        ];

        try {
            return $this->client->indices()->create($params);
        } catch (Elasticsearch\Common\Exceptions\BadRequest400Exception $e) {
            $msg = $e->getMessage();
            $msg = json_decode($msg,true);
            return $msg;
        }
    }

    // 删除索引
    public function delete_index($index_name = 'test_ik') {
        $params = ['index' => $index_name];
        $response = $this->client->indices()->delete($params);
        return $response;
    }

    // 创建文档模板
    public function create_mappings($type_name = '_doc',$index_name = 'test_ik') {

        $params = [
            'index' => $index_name,
            'body' => [
                'properties' => [
                    'id' => [
                        'type' => 'integer', // 整型
                        'index' => false,
                    ],
                    'name' => [
                        'type' => 'text', // 字符串型
                        'index' => true, // 全文搜索
                        'analyzer' => 'ik_max_word'
                    ],
                    'profile' => [
                        'type' => 'text',
                        'index' => true,
                        'analyzer' => 'ik_max_word'
                    ],
                    'age' => [
                        'type' => 'integer',
                    ],
                ]
            ]
        ];

        $response = $this->client->indices()->putMapping($params);
        return $response;
    }

    // 查看映射
    public function get_mapping($type_name = '_doc',$index_name = 'test_ik') {
        $params = [
            'index' => $index_name,
        ];
        $response = $this->client->indices()->getMapping($params);
        echo "<pre>";
        print_r($response);
        exit;
        return $response;
    }

    // 添加文档
    public function add_doc($id,$doc,$index_name = 'test_ik',$type_name = '_doc') {
        $params = [
            'index' => $index_name,
            'type' => $type_name,
            'id' => $id,
            'body' => $doc
        ];

        $response = $this->client->index($params);
        return $response;
    }

    // 判断文档存在
    public function exists_doc($id = 1,$index_name = 'test_ik',$type_name = '_doc') {
        $params = [
            'index' => $index_name,
            'type' => $type_name,
            'id' => $id
        ];

        $response = $this->client->exists($params);
        echo "<pre>";
        var_dump($response);
        exit;
        return $response;
    }


    // 获取文档
    public function get_doc($id = 1,$index_name = 'test_ik',$type_name = '_doc') {
        $params = [
            'index' => $index_name,
            'type' => $type_name,
            'id' => $id
        ];

        $response = $this->client->get($params);
        echo "<pre>";
        print_r($response);
        exit;
        return $response;
    }

    // 更新文档
    public function update_doc($id = 1,$index_name = 'test_ik',$type_name = '_doc') {
        // 可以灵活添加新字段,最好不要乱添加
        $params = [
            'index' => $index_name,
            'type' => $type_name,
            'id' => $id,
            'body' => [
                'doc' => [
                    'name' => '大王'
                ]
            ]
        ];

        $response = $this->client->update($params);
        return $response;
    }

    // 删除文档
    public function delete_doc($id = 1,$index_name = 'test_ik',$type_name = 'users') {
        $params = [
            'index' => $index_name,
            'type' => $type_name,
            'id' => $id
        ];

        $response = $this->client->delete($params);
        return $response;
    }

    // 查询文档 (分页，排序，权重，过滤)
    public function search_doc($keywords = "运维",$index_name = "test_ik",$type_name = "_doc",$from = 0,$size = 2) {
        $params = [
            'index' => $index_name,
            'type' => $type_name,
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            [ 'match' => [ 'profile' => [
                                'query' => $keywords,
                                'boost' => 3, // 权重大
                            ]]],
                            [ 'match' => [ 'name' => [
                                'query' => $keywords,
                                'boost' => 2,
                            ]]],
                        ],
                    ],
                ],
                'sort' => ['age'=>['order'=>'desc']]
                , 'from' => $from, 'size' => $size
            ]
        ];

        $results = $this->client->search($params);
        return $results;
    }
    public function insertAllData(){
        $data = db("resource_base_info_weibo")->field("id,uname,introduce")->select();
        echo "<pre>";
        print_r($data);
        exit;
    }

    private $user = [
        "admin"=>[
            "userid"=>1001,
            "username"=>"admin",
            "password"=>"123456",
            "address"=>[]
        ]
    ];
    public function login(){
        $postData = "?".file_get_contents('php://input');
        $query_arr = [];
        parse_str(parse_url($postData)["query"],$query_arr);
        if (!isset($this->user[$query_arr["name"]]) || $this->user[$query_arr["name"]]["password"] != $query_arr["password"]){
            return json_encode(["success"=>false,"msg"=>"账号密码错误"]);
        }else{
            $token = $this->user[$query_arr["name"]]["userid"];
            return json_encode(["success"=>true,"token"=>$token]);
        }
    }
    public function searchGoods(){
        $postData = "?".file_get_contents('php://input');
        $query_arr = [];
        parse_str(parse_url($postData)["query"],$query_arr);
        $url = "D:\phpStudy\WWW\myShop\shopData\shopItem.json";
        $json_data = file_get_contents($url);
        $json_data = explode("\n",$json_data);
        unset($json_data[13]);
        $shop_item = [];
        foreach ($json_data as $key => $value){
            $item = json_decode($value,true);
            if (strpos($item["s_msg"],$query_arr["s_msg"]) !== false || strpos($item["s_type"],$query_arr["s_msg"]) !== false){
                $shop_item[$key] = $item;
            }
        }
        return json_encode(["data"=>$shop_item]);
    }

    public function getDetail(){
        $postData = "?".file_get_contents('php://input');
        $query_arr = [];
        parse_str(parse_url($postData)["query"],$query_arr);
        $url = "D:\phpStudy\WWW\myShop\shopData\shopItem.json";
        $json_data = file_get_contents($url);
        $json_data = explode("\n",$json_data);
        unset($json_data[13]);
        $shop_item = "";
        foreach ($json_data as $key => $value){
            $item = json_decode($value,true);
            if ($item["s_uid"] == $query_arr["s_uid"]){
                $shop_item = $item;
            }
        }
        return json_encode($shop_item);
    }

    public function userInfo(){
        $postData = "?".file_get_contents('php://input');
        $query_arr = [];
        parse_str(parse_url($postData)["query"],$query_arr);
        $user_id = $query_arr["user_id"];
        $user_info = [];
        foreach ($this->user as $key => $value){
            if($value["userid"] == $user_id){
                $user_info = $value;
                $user_name = $value["username"];
                break;
            }
        }
        $user_info["collect"] = json_decode(Session::get($user_name));
        return json_encode($user_info);
    }

    public function collect(){
        $postData = "?".file_get_contents('php://input');
        $query_arr = [];
        parse_str(parse_url($postData)["query"],$query_arr);
        $collect = $query_arr["collect"];
        $user_id = $collect["user_id"];
        foreach ($this->user as $value){
            if ($value["userid"] == $user_id){
                $user_id = $value["username"];
                break;
            }
        }
        $goods_id = $collect["goods_id"];
        if ($collect["addCollect"]){
            $collect_goods_id = [];
            if (Session::has($user_id)){
                $collect_goods_id = json_decode(Session::get($user_id));
            }
            array_push($collect_goods_id,$goods_id);
            Session::set($user_id,json_encode($collect_goods_id));
            $msg="收藏成功";
        }else{
            $collect_goods_id = json_decode(Session::get($user_id));
            array_slice($collect_goods_id,array_search($goods_id,$collect_goods_id),1);
            if ($collect_goods_id){
                Session::set($user_id,json_encode($collect_goods_id));
            }
            $msg="取消收藏成功";
        }
        return json_encode(["code"=>1,"msg"=>$msg]);
    }

    public function addCart(){
        $postData = "?".file_get_contents('php://input');
        $query_arr = [];
        parse_str(parse_url($postData)["query"],$query_arr);
        $cart_key = "cart_".$query_arr["user_id"];
        $carts = [];
        if(Session::has($cart_key)){
            $carts = json_decode(Session::get($cart_key));
        }
        array_push($carts,$query_arr["goods_info"]);
        Session::set($cart_key,json_encode($carts));
        return json_encode(["code"=>1,"msg"=>"加入成功"]);
    }

    public function getGoodsList(){
        $postData = "?".file_get_contents('php://input');
        $query_arr = [];
        parse_str(parse_url($postData)["query"],$query_arr);
        $url = "D:\phpStudy\WWW\myShop\shopData\shopItem.json";
        $json_data = file_get_contents($url);
        $json_data = explode("\n",$json_data);
        unset($json_data[13]);
        $shop_item = [];
        foreach ($json_data as $key => $value){
            $item = json_decode($value,true);
            $shop_item[$key] = $item;
        }
        return json_encode($shop_item);
    }

    public function addSearchText(){
        $postData = "?".file_get_contents('php://input');
        $query_arr = [];
        parse_str(parse_url($postData)["query"],$query_arr);
        $search_word = $query_arr["searchText"];
        $user_id = $query_arr["user_id"];
        $search_history_key = "search_history".$user_id;
        $search_history = [];
        if(Session::has($search_history_key)){
            $search_history = json_decode(Session::get($search_history_key));
        }
        array_push($search_history,$search_word);
        Session::set($search_history_key,json_encode($search_history));
        return json_encode(["code"=>1]);
    }

    public function getSearchText(){
        $postData = "?".file_get_contents('php://input');
        $query_arr = [];
        parse_str(parse_url($postData)["query"],$query_arr);
        $user_id = $query_arr["user_id"];
        $search_history_key = "search_history".$user_id;
        $search_history = Session::get($search_history_key) ? array_unique(json_decode(Session::get($search_history_key))) : [];
        return json_encode(["code"=>1,"data"=>$search_history]);
    }

    public function deleteSearchText(){
        $postData = "?".file_get_contents('php://input');
        $query_arr = [];
        parse_str(parse_url($postData)["query"],$query_arr);
        $user_id = $query_arr["user_id"];
        $search_history_key = "search_history".$user_id;
        Session::delete($search_history_key);
    }

    public function getCart(){
        $postData = "?".file_get_contents('php://input');
        $query_arr = [];
        parse_str(parse_url($postData)["query"],$query_arr);
        $user_id = $query_arr["user_id"];
        $cart_key = "cart_".$user_id;
        $carts = [];
        if(Session::has($cart_key)){
            $carts = json_decode(Session::get($cart_key));
        }
        return json_encode(["code"=>1,"data"=>$carts]);
    }

    public function getAddress(){
        $postData = "?".file_get_contents('php://input');
        $query_arr = [];
        parse_str(parse_url($postData)["query"],$query_arr);
        $user_id = $query_arr["user_id"];
        $address_key = "address_".$user_id;
        $address_arr = [];
        if (Session::has($address_key)){
            $address_arr = json_decode(Session::get($address_key),true);
        }
        return json_encode(["code"=>1,"data"=>$address_arr]);
    }

    private function userConvertInfo($user_id){
        $user_info = [];
        foreach ($this->user as $key => $value){
            if($value["userid"] == $user_id){
                $user_info = $value;
            }
        }
        return $user_info;
    }

    public function addAddress(){
        $postData = "?".file_get_contents('php://input');
        $query_arr = [];
        parse_str(parse_url($postData)["query"],$query_arr);
        $user_id = $query_arr["user_id"];
        $address_key = "address_".$user_id;
        $address_arr = [];
        if (Session::has($address_key)){
            $address_arr = json_decode(Session::get($address_key),true);
        }
        array_push($address_arr,$query_arr["address"]);
        Session::set($address_key,json_encode($address_arr));
        return json_encode(["code"=>1]);
    }

    public function submitOrder(){
        $postData = "?".file_get_contents('php://input');
        $query_arr = [];
        parse_str(parse_url($postData)["query"],$query_arr);
        $user_id = $query_arr["user_id"];
        $order_key = "order_".$user_id;
        $order_arr = [];
        if (Session::has($order_key)){
            $order_arr = json_decode(Session::get($order_key),true);
        }
        array_push($order_arr,$query_arr["order"]);
        Session::set($order_key,json_encode($order_arr));
        return json_encode(["code"=>1]);
    }

    public function getUserInfo(){
        $postData = "?".file_get_contents('php://input');
        $query_arr = [];
        parse_str(parse_url($postData)["query"],$query_arr);
        $user_id = $query_arr["user_id"];
        $user_info_key = "user_info_".$user_id;
        $user_info = [
            "userImg"=>"https://tvax3.sinaimg.cn/crop.0.0.888.888.180/00612U1yly8gcwu8qvz1jj30oo0oogps.jpg?KID=imgbed,tva&Expires=1591966007&ssig=Trn9QDXim8",
            "userName"=>"爱喝可乐的车"
        ];
        if (Session::has($user_info_key)){
            $user_info = json_decode(Session::get($user_info_key));
        }
        return json_encode(["code"=>1,"data"=>$user_info]);
    }

    public function getSession(){
        $all = Session::get("admin");
        echo "<pre>";
        print_r($all);
        exit;
    }
}