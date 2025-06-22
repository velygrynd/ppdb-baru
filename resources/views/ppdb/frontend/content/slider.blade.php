<div class="slider1-area overlay-default">
    <div class="bend niceties preview-1">
        <div id="ensign-nivoslider-3" class="slides" style="max-height: 550px; object-fit: cover;">
            <img src="{{asset('Assets/Frontend/img/slider/foto_login.png')}}" alt="slider" title="#slider-direction-1" style="max-height: 550px; object-fit: cover;" />
            <img src="{{asset('Assets/Frontend/img/slider/foto_login.png')}}" alt="slider" title="#slider-direction-2" style="max-height: 550px; object-fit: cover;"  />
        </div>
        <div id="slider-direction-1" class="t-cn slider-direction">
            <div class="slider-content s-tb slide-1">
                <div class="title-container s-tb-c">
                    <!-- <div class="title1">Alur Pendaftaran</div> -->
                    <!-- <p>Kini Mendaftar ke RA Al Barokah lebih Mudah Daftar dari rumah.</p> -->
                    <div class="slider-btn-area">
                        <a href="{{route('register')}}" class="default-big-btn">Daftar</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div id="slider-direction-2" class="t-cn slider-direction">
            <div class="slider-content s-tb slide-2">
                <div class="title-container s-tb-c">
                    <div class="title1">Syarat Pendaftaran</div>
                    <p>Jadikan Sekolah SMK Yadika Natar sebagai tempat dirimu menggapai mimpi.</p>
                    <div class="slider-btn-area">
                        <a href="{{route('register')}}" class="default-big-btn">Daftar</a>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>

<div class="service1-area">
    <div class="service1-inner-area">
        <!-- <div class="container">
            <div class="row service1-wrapper">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 service-box1">
                    <div class="service-box-content">
                        <h3><a href="#">Beasiswa</a></h3>
                        <p>Beasiswa untuk murid berprestasi, Akademin atau Non-Akademik.</p>
                    </div>
                    <div class="service-box-icon">
                        <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 service-box1">
                    <div class="service-box-content">
                        <h3><a href="#">Pengajar Terampil</a></h3>
                        <p>Guru yang terampil di bidangnya, berpengalaman dan teruji.</p>
                    </div>
                    <div class="service-box-icon">
                        <i class="fa fa-user" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 service-box1">
                    <div class="service-box-content">
                        <h3><a href="#">Perpustakaan</a></h3>
                        <p>Fasilitas perpustakaan untuk menunjang murid dalam belajar.</p>
                    </div> -->
                    <div class="service-box-icon">
                        <i class="fa fa-book" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom styling for default-big-btn */
    .default-big-btn {
        background-color: #283046 !important;
        border: 2px solid #283046 !important;
        color: #ffffff !important;
        padding: 12px 30px;
        font-size: 16px;
        font-weight: bold;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .default-big-btn:hover {
        background-color: #1e243a !important;
        border-color: #1e243a !important;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(40, 48, 70, 0.3);
        text-decoration: none;
    }

    .default-big-btn:focus,
    .default-big-btn:active {
        background-color: #283046 !important;
        border-color: #283046 !important;
        color: #ffffff !important;
        outline: none;
        text-decoration: none;
    }

    /* Responsive adjustments for button */
    @media (max-width: 768px) {
        .default-big-btn {
            padding: 10px 25px;
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .default-big-btn {
            padding: 8px 20px;
            font-size: 13px;
        }
    }
</style>