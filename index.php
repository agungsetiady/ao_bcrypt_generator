<?php
class Bcrypt
{
    const DEFAULT_WORK_FACTOR = 7;

    public static function hash($password, $work_factor = 7)
    {
        if ( CRYPT_BLOWFISH !== 1 )
            throw new Exception('Bcrypt membutuhkan PHP 5.3 ke atas');

        if (! function_exists('openssl_random_pseudo_bytes')) {
            throw new Exception('Bcrypt membutuhkan openssl PHP extension');
        }

        if ($work_factor < 4 || $work_factor > 31) $work_factor = self::DEFAULT_WORK_FACTOR;
        $salt =
            '$2a$' . str_pad($work_factor, 2, '0', STR_PAD_LEFT) . '$' .
            substr(
                strtr(base64_encode(openssl_random_pseudo_bytes(16)), '+', '.'),
                0, 22
            )
        ;
        return crypt($password, $salt);
    }

    public static function verify($password, $stored_hash, $legacy_handler = NULL)
    {
        if ( CRYPT_BLOWFISH !== 1 )
            throw new Exception('Bcrypt membutuhkan PHP 5.3 ke atas');

        if (self::is_legacy_hash($stored_hash)) {
            if ($legacy_handler) return call_user_func($legacy_handler, $password, $stored_hash);
            else throw new Exception('Format hash tidak didukung');
        }

        return crypt($password, $stored_hash) === $stored_hash;
    }

    public static function is_legacy_hash($hash) { return substr($hash, 0, 4) != '$2a$'; }
}

$s = @$_GET['string'];
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Bcrypt Generator - AplikasiOnline</title>
    <meta name="description" content="Online Bcrypt Generator - AplikasiOnline" />
    <meta name="generator" content="aplikasionline.id">
    <link rel="icon" href="img/favicon.png" type="image/x-icon" />
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon" />
    <meta property="og:image" name="twitter:image" content="img/favicon.png">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@AplikasiOnline">
    <meta name="twitter:creator" content="@SetiadyAgung">
    <meta name="twitter:title" content="Aplikasi Online">
    <meta name="twitter:description" content="Aplikasi Online">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/3.0.0/css/ionicons.css" rel="stylesheet">
    <link href="css/theme.css" rel="stylesheet">
    <link href="css/template.css" rel="stylesheet">
  </head>
  
  <body data-spy="scroll" data-target="#navbar1" data-offset="60">

    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-primary" id="navbar1">
        <div class="container">
            <a class="navbar-brand mr-1 mb-1 mt-0" href="https://aplikasionline.id" target="_blank">Aplikasi Online</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsingNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <header class="bg-primary">
        <div class="container h-100">
            <div class="row h-100">
                <div class="col-12">
                    <div class="text-center m-0 vh-100 d-flex flex-column justify-content-center text-light">
                        <h1 class="display-4">Online Bcrypt Generator</h1>
                        <p class="lead">Sehat Tentrem Untuk Indonesia Raya</p>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="slider-container">
                                    <div class="swiper-container card-slider">
                                      <form action="" method="get">
                                          <div class="form-group">
                                              <input type="text" autocomplete="off" class="form-control" style="width:100%;font-size:30px;" value="<?php echo $s ?>" placeholder="Enter string to encrypt.." name="string" required>
                                          </div>
                                          <?php
                                          if (isset($_GET['hash'])){
                                            $q = $_GET['string'];
                                            $hash = Bcrypt::hash($q);

                                            echo '<div class="form-group"><textarea class="form-control text-success" style="font-size:30px;height:150px;width:100%;" placeholder="Result goes here..">'.$hash.'</textarea></div>';
                                          }
                                          else
                                          {

                                          }
                                          ?>
                                          <div class="form-group">
                                              <input type="submit" class="btn btn-danger btn-lg round" name="hash" value="HASH IT !">
                                          </div>
                                      </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
        
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.13.0/umd/popper.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>