<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<!-- Header Start -->
<div class="container-fluid bg-primary py-5 mb-5 page-header">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h1 class="display-3 text-white animated slideInDown">Libros</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a class="text-white" href="#">Inicio</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Libros</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<div class="container-xxl py-5">
    <div class="container">
        <div class="row pb-2">
            <div class="col-12">
                <?php if (in_array(Yii::app()->user->profile['Nombre'], ['Admin'])) { ?>
                    <button type="button" class="btn btn-primary" onclick="showFormCreateBook()">
                        Nuevo <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    </button>
                <?php } ?>
            </div>
        </div>
        <?php $this->renderPartial('/books/_datatable-books') ?>
    </div>
</div>

<?php $this->renderPartial('/books/_register-book') ?>
<?php $this->renderPartial('/books/_edit-book') ?>
<?php $this->renderPartial('/books/_view-book') ?>
<?php $this->renderPartial('/books/_show-text-book') ?>