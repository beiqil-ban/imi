<?php

declare(strict_types=1);

namespace Imi\Test\Component\Model\Base;

use Imi\Config\Annotation\ConfigValue;
use Imi\Model\Annotation\Column;
use Imi\Model\Annotation\DDL;
use Imi\Model\Annotation\Entity;
use Imi\Model\Annotation\Table;
use Imi\Model\Model as Model;

/**
 * tb_test_list 基类.
 *
 * @Entity(camel=true, bean=true)
 * @Table(name=@ConfigValue(name="@app.models.Imi\Test\Component\Model\TestList.name", default="tb_test_list"), id={"id"}, dbPoolName=@ConfigValue(name="@app.models.Imi\Test\Component\Model\TestList.poolName"))
 * @DDL(sql="CREATE TABLE `tb_test_list` (   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,   `list` varchar(255) NOT NULL DEFAULT '',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8", decode="")
 *
 * @property int|null    $id
 * @property string|null $list
 */
abstract class TestListBase extends Model
{
    /**
     * id.
     *
     * @Column(name="id", type="int", length=10, accuracy=0, nullable=false, default="", isPrimaryKey=true, primaryKeyIndex=0, isAutoIncrement=true, unsigned=true)
     */
    protected ?int $id = null;

    /**
     * 获取 id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * 赋值 id.
     *
     * @param int|null $id id
     *
     * @return static
     */
    public function setId($id)
    {
        $this->id = null === $id ? null : (int) $id;

        return $this;
    }

    /**
     * list.
     *
     * @Column(name="list", type="varchar", length=255, accuracy=0, nullable=false, default="", isPrimaryKey=false, primaryKeyIndex=-1, isAutoIncrement=false, unsigned=false)
     *
     * @var string|null
     */
    protected $list = '';

    /**
     * 获取 list.
     *
     * @return string|null
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * 赋值 list.
     *
     * @param string|null $list list
     *
     * @return static
     */
    public function setList($list)
    {
        if (\is_string($list) && mb_strlen($list) > 255)
        {
            throw new \InvalidArgumentException('The maximum length of $list is 255');
        }
        $this->list = null === $list ? null : (string) $list;

        return $this;
    }
}
