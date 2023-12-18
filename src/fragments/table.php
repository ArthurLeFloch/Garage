<?php
include_once "../utils/field_names.php";

class _TableButton
{
    private $id_prefix;
    private $button_text;
    private $button_class;
    private $id_fields;

    function __construct($id_prefix, $button_text, $id_fields = array(), $is_primary = true)
    {
        $this->id_prefix = $id_prefix;
        $this->button_text = htmlspecialchars($button_text);
        if ($is_primary) {
            $this->button_class = "btn btn-primary";
        } else {
            $this->button_class = "btn btn-danger";
        }
        $this->id_fields = $id_fields;
    }

    function show($row)
    {
        $field_string = "";
        foreach ($this->id_fields as $field) {
            $field_string .= "-$row[$field]";
        }
        echo "<a href='#' class='" . $this->button_class . "' id='" . $this->id_prefix . $field_string . "'>" . $this->button_text . "</a>";
    }
}

class Table
{
    private $table_id;
    private $title;
    private $has_title;
    private $buttons;
    private $hidden_fields;
    private $extra_columns;

    function __construct($table_id, $title = "")
    {
        $this->table_id = $table_id;
        $this->buttons = array();
        $this->title = htmlspecialchars($title);
        $this->has_title = $this->title != "";
        $this->hidden_fields = array();
        $this->extra_columns = array();
    }

    function add_button($id_prefix, $button_text, $id_fields = array(), $is_primary = true)
    {
        $this->buttons[] = new _TableButton($id_prefix, $button_text, $id_fields, $is_primary);
    }

    function set_hidden_fields($hidden_fields)
    {
        $this->hidden_fields = $hidden_fields;
    }

    function add_column($column_name, $function, $arg_ids = array())
    {
        $name = htmlspecialchars($column_name);
        $this->extra_columns[] = array($name, $function, $arg_ids);
    }

    private function field_count($res)
    {
        $res = pg_num_fields($res) - count($this->hidden_fields) + count($this->extra_columns);
        if (count($this->buttons) == 0) {
            return $res;
        } else {
            return $res + 1;
        }
    }

    function show($res)
    {
        // Table header
        echo "<table class='table table-striped table-bordered' id='" . $this->table_id . "' style='overflow-x: auto; display: block; text-align: center'>";
        echo "<thead>";

        if ($this->has_title) {
            echo "<tr><td colspan='" . $this->field_count($res) . "'>";
            echo "<p><b>" . $this->title . "</b></p>";
            echo "</td></tr>";
        }

        for ($i = 0; $i < pg_num_fields($res); $i++) {
            if (!in_array($i, $this->hidden_fields)) {
                echo "<th scope='col'>" . convert(pg_field_name($res, $i)) . "</th>";
            }
        }

        for ($i = 0; $i < count($this->extra_columns); $i++) {
            echo "<th scope='col'>" . $this->extra_columns[$i][0] . "</th>";
        }

        if (count($this->buttons) > 0) {
            echo "<th scope='col'></th>";
        }

        echo "</tr></thead>";

        // Table body
        echo "<tbody table-group-divider>";

        while ($data = pg_fetch_array($res)) {
            echo "<tr>";
            for ($i = 0; $i < pg_num_fields($res); $i++) {
                if (!in_array($i, $this->hidden_fields)) {
                    echo '<td>' . htmlspecialchars($data[$i]) . '</td>';
                }
            }

            // Add extra columns
            foreach ($this->extra_columns as $column) {
                $function = $column[1];
                $arg_ids = $column[2];
                $args = array();
                foreach ($arg_ids as $id) {
                    $args[] = $data[$id];
                }
                echo "<td>" . $function(...$args) . "</td>";
            }

            // Add buttons
            if (count($this->buttons) > 0) {
                echo "<td>";
                echo "<div class='btn-group' role='group'>";
                foreach ($this->buttons as $button) {
                    $button->show($data);
                }
                echo "</div>";
                echo "</td>";
            }
            echo '</tr>' . "\n";
        }

        echo "</tbody>";
        echo "</table>";
    }
}
