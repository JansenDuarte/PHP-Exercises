<?php

require __DIR__ . "/BinaryTree.php";

$tree = new BinaryTree();

$tree->add(6);
$tree->add(3);
$tree->add(19);
$tree->add(30);
$tree->add(12);
$tree->add(1);
$tree->add(4);

echo $tree->printTree();
