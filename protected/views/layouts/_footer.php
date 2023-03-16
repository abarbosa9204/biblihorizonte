    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Enlaces rápidos</h4>
                    <a href="<?php echo Yii::app()->baseUrl; ?>/site/index" class="btn btn-link">Inicio</a>
                    <?php if (!Yii::app()->user->isGuest) { ?>
                        <?php if (in_array(Yii::app()->user->profile['Nombre'], ['Admin'])) { ?>
                            <a href="<?php echo Yii::app()->baseUrl; ?>/books/books" class="btn btn-link">Libros</a>
                            <a href="<?php echo Yii::app()->baseUrl; ?>/site/reservas" class="btn btn-link">Mis reservas</a>
                            <a href="<?php echo Yii::app()->baseUrl; ?>/admin/users" class="btn btn-link">Usuarios</a>
                        <?php } else { ?>
                            <a href="<?php echo Yii::app()->baseUrl; ?>/books/books" class="btn btn-link">Libros</a>
                            <a href="<?php echo Yii::app()->baseUrl; ?>/site/reservas" class="btn btn-link">Mis reservas</a>
                        <?php } ?>
                    <?php } ?>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Contacto</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>123 Street, New York, USA</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@example.com</p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Galeria</h4>
                    <div class="row g-2 pt-2">
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/img/course-1.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/img/course-2.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/img/course-3.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/img/course-2.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/img/course-3.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="<?php echo Yii::app()->baseUrl; ?>/themes/elearning-1.0.0/img/course-1.jpg" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Solicitud de registro</h4>
                    <p>Solicitar registro para acceder a la información de la biblioteca.</p>
                    <div class="position-relative mx-auto" style="max-width: 400px;">
                        <input class="form-control border-0 w-100 py-3 ps-4 pe-5" type="text" placeholder="Email">
                        <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">Enviar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">Bibliohorizonte</a>, Todos los derechos reservados.

                        <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                        Diseñado por <a class="border-bottom" href="#">Angel Barbosa</a><br><br>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <div class="footer-menu">
                            <a href="<?php echo Yii::app()->baseUrl; ?>/site/index">Inicio</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->