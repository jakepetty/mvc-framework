<?php
namespace App;

class View
{
    protected $title = 'Home';

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function render($path, $vars = [])
    {
        // Pass variables to view
        $this->viewVars = $vars;

        // Replace . format with path format
        $path = str_replace('.', DS, $path);

        // Define the view file
        $this->view = __VIEWS__ . DS . $path . '.php';

        // Display this error when view file is missing
        if (!file_exists($this->view)) {
            $this->view = __VIEWS__ . DS . 'errors' . DS . 'missing_view.php';
        }

        // Render the layout
        require __VIEWS__ . DS . 'layouts' . DS . 'default.php';
    }

    public function content()
    {
        // Setup view variables
        foreach ($this->viewVars as $var => $data) {
            $$var = $data;
        }

        // Render View File
        require $this->view;
    }
}
