<?php

class BinaryTree
{
    private $root;

    public function __construct()
    {
        $this->root = null;
    }

    public function isEmpty()
    {
        return $this->root === null;
    }

    public function add($value)
    {
        $node = new BinaryNode($value);

        if ($this->isEmpty()) {
            $this->root = $node;
            return true;
        } else {
            return $this->recurAddNode($node, $this->root);
        }
    }

    private function recurAddNode($node, $current)
    {
        $added = false;

        #kinda ugly... looping while false just to break out when not false
        while ($added === false) {
            if ($node->value < $current->value) {
                if ($current->left === null) {
                    $current->addChildren($node, $current->right);
                    $added = $node;
                    break;
                } else {
                    $current = $current->left;
                    return $this->recurAddNode($node, $current);
                }
            } else {
                break;
            }
        }
        return $added;
    }

    public function removeNode($node)
    {
        if ($this->isEmpty()) {
            return false;
        }

        $retrievedNode = $this->getNode($node);

        if ($retrievedNode == false) {
            return false;
        }

        #remove root node
        if ($retrievedNode->value === $this->root->value) {
            $current = $this->root->left;

            while ($current->right != null) {
                $current == $current->right;
                continue;
            }

            $current->left = $this->root->left;
            $current->right = $this->root->right;

            $parent = $this->recurGetParent($current, $this->root);
            $parent->right = $current->left;

            $this->root = $current;

            return true;
        }

        #remove node with 2 children

        #remove node with 1 child
        if ($retrievedNode->left || $retrievedNode->right !== null) {

        }

        #remove leaf node
        if ($retrievedNode->left === null && $retrievedNode->right === null) {
            $parent = $this->recurGetParent($retrievedNode, $this->root);
            #dont know if this comparison works
            if ($parent->left->value && $retrievedNode->value === $parent->left->value) {
                $parent->left = null;
                return true;
            } elseif ($parent->right->value && $retrievedNode->value === $parent->right->value) {
                $parent->right = null;
                return true;
            }
        }
    }

    public function getNode($node)
    {
        if ($this->isEmpty()) {
            return false;
        }
        $current = $this->root;
        if ($node->value === $this->root->value) {
            return true;
        } else {
            return $this->recurGetNode($node, $current);
        }
    }

    private function recurGetNode($node, $current)
    {
        $exists = false;

        #kinda ugly... looping while false just to break out when not false
        while ($exists === false) {
            if ($node->value < $current->value) {
                if ($current->left === null) {
                    break;
                } elseif ($node->value == $current->left->value) {
                    $exists = $current->left;
                    break;
                } else {
                    $current = $current->left;
                    return $this->recurGetNode($node, $current);
                }
            } elseif ($node->value > $current->value) {
                if ($current->right === null) {
                    break;
                } elseif ($node->value == $current->right->value) {
                    $exists = $current->right;
                    break;
                } else {
                    $current = $current->right;
                    return $this->recurGetNode($node, $current);
                }
            }
        }

        return $exists;
    }

    private function recurGetParent($child, $current)
    {
        $parent = false;
        while ($parent === false) {
            if ($child->value < $current->value) {
                if ($child->value === $current->left->value) {
                    $parent = $current;
                    break;
                } else {
                    return $this->recurGetParent($child, $current->left);
                }
            } elseif ($child->value > $current->value) {
                if ($child->value === $current->right->value) {
                    $parent = $current;
                    break;
                } else {
                    return $this->recurGetParent($child, $current->right);
                }
            } else {
                break;
            }
        }
        return $parent;
    }
}

class BinaryNode
{
    public $level;
    public $left;
    public $right;
    public $value;

    public function __construct(int $value = null)
    {
        $this->level = null;
        $this->left = null;
        $this->right = null;
        $this->value = $value;
    }

    public function addChildren($left, $right)
    {
        $this->left = $left;
        $this->right = $right;
    }
}