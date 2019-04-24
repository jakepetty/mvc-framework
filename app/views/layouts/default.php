<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $this->getTitle(); ?></title>
    <link rel="stylesheet" href="<?php echo asset('/css/app.css'); ?>">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#"><?php echo config('app.name', 'Micro Framework'); ?></a>
            <div class="ml-auto">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item active">
                            <a class="nav-link" href="<?php echo route('home.index'); ?>">Home <span class="sr-only">(current)</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div class="container pt-5">
        <?php
        $this->content();
        ?>
    </div>
    <script src="<?php echo asset('/js/app-min.js'); ?>"></script>
</body>

</html>