<?php

class BinaryNode
{
    public $level;
    public $left;
    public $right;
    public $value;


    private $parent;



    public function __construct(int $value = null)
    {
        $this->level = null;
        $this->left = null;
        $this->right = null;
        $this->parent = null;
        $this->value = $value;
    }

    public function addChildren($left, $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    public function updateParent(BinaryNode &$parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }





    public function __tostring(): string
    {
        $ret = "\nBinaryNode\n{\n\tValue:";
        $ret .= "\t" . $this->value . "\n}";
        return $ret;
    }
}