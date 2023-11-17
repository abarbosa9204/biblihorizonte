<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<!-- Header Start -->
<div class="container-fluid bg-primary py-5 mb-5 page-header">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h1 class="display-3 text-white animated slideInDown">Reservas</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a class="text-white" href="#">Inicio</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Reservas</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<div class="container-xxl py-5">
    <div class="container">
        <?php $this->renderPartial('/booksReservation/_datatable-books-reservation') ?>
    </div>
</div>
<?php $this->renderPartial('/books/_view-book') ?>
<?php $this->renderPartial('/books/_show-text-book') ?>