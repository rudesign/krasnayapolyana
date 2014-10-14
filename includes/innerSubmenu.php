<?php class_exists('Core', false) or die();

$query = Inner::set();
$query->condition = 'parent = '.Core::$item['id'];
$query->order = 'ord ASC';

if ($rows = Inner::get($query)) {
    echo '<div class="common-submenu extra-section">';
    foreach ($rows as $row) {
        $uri = '/' . Router::$request->parsed->origin[0] . '/' . $row['alias'] . '/';
        echo '<a href="' . $uri . '">' . $row['name'] . '</a>';
    }
    echo '</div>';
}

?>