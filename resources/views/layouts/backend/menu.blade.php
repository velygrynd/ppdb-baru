<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto"><a class="navbar-brand" href="/home"><span class="brand-logo">
                        <img src="{{ asset('Assets/Frontend/img/foto_logo.png') }}" alt="Logo" width="42" height="42">
                    </span>
                    <h2 class="brand-text">Dashboard</h2>
                </a></li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i
                        class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i
                        class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc"
                        data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>

    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item {{ (request()->is('home')) ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="/home"><i data-feather="home"></i>
                    <span class="menu-title text-truncate" data-i18n="Dashboards">Dashboard</span>
                </a>
            </li>

            {{-- MENU ADMIN --}}
            @if (Auth::user()->role == 'Admin')
            <li class=" nav-item">
                <a class="d-flex align-items-center" href="#"><i data-feather="database"></i>
                    <span class="menu-title text-truncate" data-i18n="Data Sekolah">Data Sekolah</span>
                </a>
                <ul class="menu-content">
                    <li class="nav-item {{ (request()->is('program-studi')) ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href=" {{route('program-studi.index')}} "><i
                                data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="Basic">jadwal Pelajaran</span>
                        </a>
                    </li>
                    <li class="nav-item {{ (request()->is('backend-event')) ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href=" {{route('backend-event.index')}} "><i
                                data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="Basic">Event</span>
                        </a>
                    </li>

                    <li><a class="d-flex align-items-center" href="#"><i data-feather="circle"></i><span
                                class="menu-item text-truncate" data-i18n="Second Level">Tentang</span></a>
                        <ul class="menu-content">
                            <li class="nav-item {{ (request()->is('backend-profile-sekolah')) ? 'active' : '' }}">
                                <a class="d-flex align-items-center"
                                    href="{{route('backend-profile-sekolah.index')}}"><span
                                        class="menu-item text-truncate" data-i18n="Third Level">Profile
                                        Sekolah</span></a>
                            </li>
                            <li class="nav-item {{ (request()->is('backend-visimisi')) ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{route('backend-visimisi.index')}}"><span
                                        class="menu-item text-truncate" data-i18n="Third Level">Visi dan Misi</span></a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class=" nav-item">
                <a class="d-flex align-items-center" href="#"><i data-feather="file-text"></i>
                    <span class="menu-title text-truncate" data-i18n="Card">Berita</span>
                </a>
                <ul class="menu-content">
            </li>
            <li class="nav-item {{ (request()->is('backend-kegiatan')) ? 'active' : '' }}">
                <a class="d-flex align-items-center" href=" {{route('backend-kegiatan.index')}} "><i
                        data-feather="circle"></i>
                    <span class="menu-item text-truncate" data-i18n="Basic"> Dokumentasi</span>
                </a>
            </li>
        </ul>
        </li>
        <!-- <li class=" nav-item">
                        <a class="d-flex align-items-center" href="#"><i data-feather="globe"></i>
                            <span class="menu-title text-truncate" data-i18n="Card">Website</span>
                        </a>
                        <ul class="menu-content">
                            <li class="nav-item {{ (request()->is('backend-about')) ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href=" {{route('backend-about.index')}} "><i data-feather="circle"></i>
                                    <span class="menu-item text-truncate" data-i18n="Basic">About</span>
                                </a>
                            </li>
                            <li class="nav-item {{ (request()->is('backend-imageslider')) ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href=" {{route('backend-imageslider.index')}} "><i data-feather="circle"></i>
                                    <span class="menu-item text-truncate" data-i18n="Basic">Gambar Slider</span>
                                </a>
                            </li>
                            <li class="nav-item {{ (request()->is('backend-video')) ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href=" {{route('backend-video.index')}} "><i data-feather="circle"></i>
                                    <span class="menu-item text-truncate" data-i18n="Basic">Video</span>
                                </a>
                            </li>

                            <li class="nav-item {{ (request()->is('backend-footer')) ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href=" {{route('backend-footer.index')}} "><i data-feather="circle"></i>
                                    <span class="menu-item text-truncate" data-i18n="Basic">Footer</span>
                                </a>
                            </li>

                        </ul>
                    </li> -->

        <li class=" nav-item">
            <a class="d-flex align-items-center" href="#"><i data-feather="users"></i>
                <span class="menu-title text-truncate" data-i18n="Card">Pengguna</span>
            </a>
            <ul class="menu-content">
                <li class="nav-item {{ (request()->is('backend-pengguna-pengajar')) ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href=" {{route('backend-pengguna-pengajar.index')}} "><i
                            data-feather="circle"></i>
                        <span class="menu-item text-truncate" data-i18n="Basic">Guru</span>
                    </a>
                </li>

                <li class="nav-item {{ (request()->is('backend-pengguna-murid')) ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href=" {{route('backend-pengguna-murid.index')}} "><i
                            data-feather="circle"></i>
                        <span class="menu-item text-truncate" data-i18n="Basic">Murid</span>
                    </a>
            </ul>
        </li>

        <li class="nav-item {{ (request()->is('spp/murid')) ? 'active' : '' }}">
            <a class="d-flex align-items-center" href=" {{route('spp.murid.index')}} "><i data-feather="users"></i>
                <span class="menu-title text-truncate" data-i18n="Books">Pembayaran</span>
            </a>
        </li>

        <li class="nav-item {{ (request()->is('settings/spp/murid')) ? 'active' : '' }}">
            @if(Auth::user()->role == 'Admin')
            <a class="d-flex align-items-center" href="{{ route('backend.settings.index') }}">
                <i data-feather="credit-card" class="mr-50"></i>
                <span class="menu-title text-truncate" data-i18n="Pembayaran">Setting SPP</span>
            </a>
            @endrole
        </li>

        <li class="nav-item {{ (request()->is('ppdb/data-murid')) ? 'active' : '' }}">
            <a class="d-flex align-items-center" href="{{route('data-murid.index')}}"><i data-feather="book"></i>
                <span class="menu-title text-truncate" data-i18n="Data Calon Murid">Data Calon Murid</span>
            </a>
        </li>

        <li class="nav-item {{ (request()->is('ppdb/buka-tutup-ppd')) ? 'active' : '' }}">
            <a class="d-flex align-items-center" href="{{ route('ppd.index') }}">
                <i data-feather="lock"></i>
                <span class="menu-title text-truncate" data-i18n="Buka Tutup PPDB">Buka Tutup PPDB</span>
            </a>
        </li>




        {{-- MENU GURU --}}
        @elseif(Auth::user()->role == 'Guru')
        <li class="nav-item">
            <a class="d-flex align-items-center" href="#"><i data-feather="credit-card"></i>
                <span class="menu-title text-truncate" data-i18n="Card">Data Kelas</span>
            </a>
            <ul class="menu-content">
                <li>
                    <a class="d-flex align-items-center" href="{{route('muridA')}}"><i data-feather="circle"></i>
                        <span class="menu-item text-truncate" data-i18n="Basic">Kelas A</span>
                    </a>
                </li>
                <li>
                    <a class="d-flex align-items-center" href="{{route('muridB')}}"><i data-feather="circle"></i>
                        <span class="menu-item text-truncate" data-i18n="Basic">Kelas B</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="d-flex align-items-center" href="#"><i data-feather="credit-card"></i>
                <span class="menu-title text-truncate" data-i18n="Card">Jadwal Pelajaran</span>
            </a>
            <ul class="menu-content">
                <li>
                    <a class="d-flex align-items-center" href="{{route('kelasA')}}"><i data-feather="circle"></i>
                        <span class="menu-item text-truncate" data-i18n="Basic">Kelas A</span>
                    </a>
                </li>
                <li>
                    <a class="d-flex align-items-center" href="{{route('kelasB')}}"><i data-feather="circle"></i>
                        <span class="menu-item text-truncate" data-i18n="Basic">Kelas B</span>
                    </a>
                </li>
            </ul>
        </li>
        {{-- MENU GUEST --}}
        @elseif(Auth::user()->role == 'Guest')
        <li class="nav-item {{ (request()->is('ppdb/form-pendaftaran')) ? 'active' : '' }}">
            <a class="d-flex align-items-center" href="{{ route('ppdb.form-pendaftaran') }}">
                <i data-feather="book"></i>
                <span class="menu-title text-truncate" data-i18n="Pendaftaran">Pendaftaran</span>
            </a>
        </li>

        {{-- MENU PPDB --}}
        @elseif(Auth::user()->role == 'PPDB')
        <li class="nav-item {{ (request()->is('ppdb/data-murid')) ? 'active' : '' }}">
            <a class="d-flex align-items-center" href="{{route('data-murid.index')}}"><i data-feather="book"></i>
                <span class="menu-title text-truncate" data-i18n="Data Calon Murid">Data Calon Murid</span>
            </a>
        </li>





        {{-- MENU MURID --}}
        @elseif(Auth::user()->role == 'Murid')

        <li class="nav-item {{ (request()->is('murid/pembayaran*')) ? 'active' : '' }}">
            <a class="d-flex align-items-center" href="{{ route('murid.pembayaran.index') }}"><i
                    data-feather="dollar-sign"></i>
                <span class="menu-title text-truncate" data-i18n="Books">Pembayaran</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="d-flex align-items-center" href="#"><i data-feather="credit-card"></i>
                <span class="menu-title text-truncate" data-i18n="Card">Jadwal Pelajaran</span>
            </a>
            <ul class="menu-content">
                @if(Auth::user()->kelas_id == '1')
                <li>
                    <a class="d-flex align-items-center" href="{{ route('murid.jurusan', ['kelas_id' => 1]) }}"><i
                            data-feather="circle"></i>
                        <span class="menu-item text-truncate" data-i18n="Basic">Kelas A</span>
                    </a>
                </li>
                @elseif(Auth::user()->kelas_id == '2')
                <li>
                    <a class="d-flex align-items-center" href="{{ route('murid.jurusan', ['kelas_id' => 2]) }}"><i
                            data-feather="circle"></i>
                        <span class="menu-item text-truncate" data-i18n="Basic">Kelas B</span>
                    </a>
                </li>
                @else
                <li>
                    <span class="menu-item text-truncate text-muted">Kelas tidak dikenal</span>
                </li>
                @endif
            </ul>
        </li>
        {{-- MENU BENDAHARA --}}
        @elseif(Auth::user()->role == 'Bendahara')
        <li class="nav-item {{ (request()->routeIs('spp.admin.*')) ? 'active' : '' }}">
            <a class="d-flex align-items-center" href=" {route('spp.murid.index')}}"><i data-feather="users"></i>
                <span class="menu-title text-truncate" data-i18n="Books">Pembayaran</span>
            </a>
        </li>
        @endif
        </ul>
    </div>
</div>