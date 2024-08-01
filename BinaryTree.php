<?php

require __DIR__ . "/BinaryNode.php";


//TODO my tree is not self-centering....

//FIXME: I did everything in a rush... Code is kinda shitty, and has a lot o room for improvement and cleaning up
class BinaryTree
{
    private $root;
    private $searchedLevel = 1;
    private $maxLevel = 0;

    private array $items = [];

    public function __construct()
    {
        $this->root = null; //doesnt seem right to have a tree without a root....
    }

    public function add(int $value)
    {
        if (in_array($value, $this->items)) {
            echo "\n\nTrying to add duplicate value: $value. Aborting addition to the tree.\n\n";
            return false;
        }

        $node = new BinaryNode($value);

        if ($this->root === null) {
            $this->items[] = $node->value;
            $node->level = 0;
            $this->root = $node;
            return true;
        } else {
            return $this->recurAddNode($node, $this->root);
        }
    }

    private function recurAddNode(BinaryNode $node, BinaryNode $current)
    {
        $added = false;

        while ($added === false) {
            if ($node->value < $current->value) {
                if ($current->left === null) {
                    $this->internalAddNode($node, $current, Side::LEFT);
                    $added = $node;
                    break;
                } else {
                    $current = $current->left;
                    $this->searchedLevel++;
                    return $this->recurAddNode($node, $current);
                }
            } elseif ($node->value > $current->value) {
                if ($current->right === null) {
                    $this->internalAddNode($node, $current, Side::RIGHT);
                    $added = $node;
                    break;
                } else {
                    $current = $current->right;
                    $this->searchedLevel++;
                    return $this->recurAddNode($node, $current);
                }
            }
        }
        $this->searchedLevel = 1;
        return $added;
    }

    private function internalAddNode(BinaryNode &$node, BinaryNode &$parent, int $side)
    {
        switch ($side) {
            case Side::LEFT:
                $parent->addChildren($node, $parent->right);
                break;
            case Side::RIGHT:
                $parent->addChildren($parent->left, $node);
                break;
        }
        $node->updateParent($parent);
        $node->level = $this->searchedLevel;

        if ($this->searchedLevel > $this->maxLevel) {
            $this->maxLevel++;
        }

        $this->items[] = $node->value;
    }

    public function removeNode($value)
    {
        if ($this->root === null) {
            return false;
        }

        if (!in_array($value, $this->items)) {
            return false;
        }

        $retrievedNode = $this->getNode($value);

        if ($retrievedNode == false) {
            return false;
        }

        //remove root node
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

        //remove node with 2 children

        //remove node with 1 child
        if ($retrievedNode->left xor $retrievedNode->right !== null) {
        }

        //remove leaf node
        if ($retrievedNode->left === null && $retrievedNode->right === null) {
            $parent = $this->recurGetParent($retrievedNode, $this->root);
            //dont know if this comparison works
            if ($parent->left->value && $retrievedNode->value === $parent->left->value) {
                $parent->left = null;
                return true;
            } elseif ($parent->right->value && $retrievedNode->value === $parent->right->value) {
                $parent->right = null;
                return true;
            }
        }
    }

    public function getNode($value): BinaryNode|bool
    {
        if ($this->root === null || !in_array($value, $this->items)) {
            return false;
        }

        $current = $this->root;

        if ($value === $this->root->value) {
            return $this->root;
        } else {
            return $this->recurGetNode($value, $current);
        }
    }

    private function recurGetNode($value, $current): BinaryNode|bool
    {
        $exists = false;

        //kinda ugly... looping while false just to break out when not false
        while ($exists === false) {
            if ($value < $current->value) {
                if ($current->left === null) {
                    break;
                } elseif ($value == $current->left->value) {
                    $exists = $current->left;
                    break;
                } else {
                    $current = $current->left;
                    return $this->recurGetNode($value, $current);
                }
            } elseif ($value > $current->value) {
                if ($current->right === null) {
                    break;
                } elseif ($value == $current->right->value) {
                    $exists = $current->right;
                    break;
                } else {
                    $current = $current->right;
                    return $this->recurGetNode($value, $current);
                }
            }
        }

        return $exists;
    }

    //this is kinda pointless... could have a parent variable in the node
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

    public function printTree()
    {
        $ret = "Tree Info:\n\n";
        $ret .= "\tNº of items: " . count($this->items) . "\n\n";
        $ret .= "\tValues of items: ";

        foreach ($this->items as $value) {
            $ret .= $value . ", ";
        }

        //TODO: figure out how to traverse the tree to show all the values and its relations
        $ret .= "\n\n\n" . $this->traverseTree();

        return $ret;
    }

    private function traverseTree(): string
    {

        if ($this->root === null) {
            return "Empty tree... try adding a value with addNode(int value)";
        }

        //DEBUG ugly, but I'm just trying to understand how to print this
        $aux = $this->maxLevel * 2;

        $ret = str_repeat(" ", $aux);
        $value = sprintf("[%s]", $this->root->value);
        $ret .= $value . str_repeat(" ", $aux) . "\n";

        $aux--;

        $ret .= str_repeat(" ", $aux) . "/    \\" . str_repeat(" ", $aux) . "\n";

        $aux--;

        $value = sprintf("[%s]", $this->root->left->value);
        $ret .= str_repeat(" ", $aux) . $value;
        $value = sprintf("[%s]", $this->root->right->value);
        $ret .= str_repeat(" ", $aux) . $value;

        return $ret;
    }
}

class Side
{
    const LEFT = -1;
    const RIGHT = 1;
}
