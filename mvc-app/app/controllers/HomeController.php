<?php 

class HomeController {

   
    public function index() {

        // $database = Database::getInstance();
        // $conn = $database->getConnection();
        // $conn->

        $data = [
            'title' => 'Home Page',
            'message' => 'Welcome to the Home Page',
        ];
        render('home/index', $data, 'layouts/hero_layout');
    }


    public function about() {
        $data = [
            'title' => 'About Page',
            'message' => 'Welcome to the About Page',
        ];
        render('home/about', $data);
    }
    
    public function contact() {
        $data = [
            'title' => 'Contact Page',
            'message' => 'Welcome to the Contact Page',
        ];
        render('home/contact', $data);
    }

    
}