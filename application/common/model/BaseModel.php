<?php
/**
 * 公共基础模型类
 * @date:  2018/6/24 20:33
 * @author: daishunxin <admin@shunxin66.com>
 */

namespace app\common\model;

use think\Model;

class BaseModel extends Model {
    protected $autoWriteTimestamp = true;
    protected $createTime = 'add_time';
    protected $updateTime = 'update_time';

    public function __construct($data = []) {
        parent::__construct($data);
    }

    /**
     * 调用存储过程获取返回结果集
     * @param $procedure [存储过程]
     * @param array $parameter [存储过程参数]
     * @return array|bool
     */
//    protected function call($procedure, $parameter = []) {
//        $sp = $this->db('cat')->getPdo()->prepare($procedure);
//        foreach ($parameter as $key => $value) {
//            $sp->bindValue(is_int($key) ? $key + 1 : $key, $value);
//        }
//        if ($sp->execute()) {
//            return $sp->fetchAll(\PDO::FETCH_CLASS);
//        }
//        return false;
//    }
}