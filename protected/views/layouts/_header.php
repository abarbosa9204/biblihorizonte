<!-- Navbar Start -->
<nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
    <a href="index.html" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
        <h2 class="m-0 text-primary"><i class="fa fa-book me-3"></i><?php echo CHtml::encode(Yii::app()->name); ?></h2>
    </a>
    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0">
            <a href="<?php echo Yii::app()->baseUrl; ?>/site/index" class="nav-item nav-link active">Inicio</a>
            <?php if (!Yii::app()->user->isGuest) { ?>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Mi espacio</a>
                    <div class="dropdown-menu fade-down m-0">
                        <?php if (in_array(Yii::app()->user->profile['Nombre'], ['Admin'])) { ?>
                            <a href="<?php echo Yii::app()->baseUrl; ?>/books/books" class="dropdown-item">Libros</a>
                            <a href="<?php echo Yii::app()->baseUrl; ?>/site/reservas" class="dropdown-item">Mis reservas</a>
                            <a href="<?php echo Yii::app()->baseUrl; ?>/admin/users" class="dropdown-item">Usuarios</a>
                        <?php } else { ?>
                            <a href="<?php echo Yii::app()->baseUrl; ?>/books/books" class="dropdown-item">Libros</a>
                            <a href="<?php echo Yii::app()->baseUrl; ?>/site/reservas" class="dropdown-item">Mis reservas</a>
                        <?php } ?>
                    </div>
                </div>
                <a href="<?php echo Yii::app()->baseUrl; ?>/site/logout" class="nav-item nav-link">Salir</a>
            <?php } else { ?>
                <a href="<?php echo Yii::app()->baseUrl; ?>/site/login" class="nav-item nav-link">Login</a>
            <?php } ?>
        </div>
    </div>
</nav>
<!-- Navbar End -->