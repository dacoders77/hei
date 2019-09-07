<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{{ config('app.name') }}</title>
    <style>
    /* -------------------------------------
        INLINED WITH htmlemail.io/inline
    ------------------------------------- */
    /* -------------------------------------
        RESPONSIVE AND MOBILE FRIENDLY STYLES
    ------------------------------------- */
    @media only screen and (max-width: 620px) {
      table[class=body] h1 {
        font-size: 28px !important;
        margin-bottom: 10px !important;
      }
      table[class=body] p,
            table[class=body] ul,
            table[class=body] ol,
            table[class=body] td,
            table[class=body] span,
            table[class=body] a {
        font-size: 16px !important;
      }
      table[class=body] .wrapper,
            table[class=body] .article {
        padding: 10px !important;
      }
      table[class=body] .content {
        padding: 0 !important;
      }
      table[class=body] .container {
        padding: 0 !important;
        width: 100% !important;
      }
      table[class=body] .main {
        border-left-width: 0 !important;
        border-radius: 0 !important;
        border-right-width: 0 !important;
      }
      table[class=body] .btn table {
        width: 100% !important;
      }
      table[class=body] .btn a {
        width: 100% !important;
      }
      table[class=body] .img-responsive {
        height: auto !important;
        max-width: 100% !important;
        width: auto !important;
      }
    }

    /* -------------------------------------
        PRESERVE THESE STYLES IN THE HEAD
    ------------------------------------- */
    @media all {
      .ExternalClass {
        width: 100%;
      }
      .ExternalClass,
            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass font,
            .ExternalClass td,
            .ExternalClass div {
        line-height: 100%;
      }
      .apple-link a {
        color: inherit !important;
        font-family: inherit !important;
        font-size: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
        text-decoration: none !important;
      }
      .btn-primary table td:hover {
        background-color: #34495e !important;
      }
      .btn-primary a:hover {
        background-color: #34495e !important;
        border-color: #34495e !important;
      }
    }
    </style>
  </head>
  <body class="" style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
    <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">
      <tr>
        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
        <td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">
          <div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">

            <!-- START CENTERED WHITE CONTAINER -->
            <span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">{{ config('app.name') }} | {{ preg_replace('/\s+/', ' ', strip_tags($__env->yieldContent('content'))) }}</span>
            <table class="main" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff;">

              <tr>
                <td style="padding:0;font-size:0;">
                  <img src="{{ asset('assets/images/Dulux/DuluxEmailHeader01.jpg') }}" alt="Dulux" width="100%">
                </td>
              </tr>

              <!-- START MAIN CONTENT AREA -->
              <tr>
                <td style="padding:0;">
                  @yield('content')
                </td>
              </tr>

              <tr>
                <td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 45px 50px; color:#ffffff; background-color: #0b151e;">
                  <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                    <tr>
                      <td style="font-family: sans-serif; font-size: 15px; font-weight:800; vertical-align: middle;color:#ffffff; text-align: center; padding-bottom: 20px;text-transform: uppercase;">Promotion runs from 26th Aug &ndash; 20th September 2019</td>
                    </tr>
                    <tr>
                      <td>
                        <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                          <tr>
                            <td style="font-family: sans-serif; font-size: 12px; vertical-align: middle;color:#ffffff; text-align: center;" width="100">
                              <a href="{{ URL::to('/') }}"><img src="{{ asset('assets/images/Dulux/Logolarge@2x.png') }}" alt="Dulux" width="100"></a>
                            </td>
                            <td style="font-family: sans-serif; font-size: 12px; vertical-align: middle;color:#ffffff; text-align: center;">
                              <a href="{{ route('campaign_1.pages','terms') }}" style="color: #ffffff; text-decoration: none;" target="_blank">Terms & Conditions</a>
                              <span style="display: inline-block; margin-left: 5px; margin-right: 5px;"></span>
                              <a href="{{ route('campaign_1.pages','faqs') }}" style="color: #ffffff; text-decoration: none;" target="_blank">FAQs</a>
                              <span style="display: inline-block; margin-left: 5px; margin-right: 5px;"></span>
                              <a href="{{ route('campaign_1.pages','privacy') }}" style="color: #ffffff; text-decoration: none;" target="_blank">Privacy Policy</a>
                              <span style="display: inline-block; margin-left: 5px; margin-right: 5px;"></span>
                              <a href="{{ route('campaign_1.pages','contact-us') }}" style="color: #ffffff; text-decoration: none;" target="_blank">Contact Us</a>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td style="font-family: sans-serif; font-size: 9px; vertical-align: middle;color:#ffffff; text-align: center; padding-top: 20px;">The F1 FORMULA 1 logos, F1 logos, F1, FORMULA 1, FIA FORMULA ONE WORLD CHAMPIONSHIP, GRAND PRIX and related marks are trade marks of Formula One Licensing BV, a Formula 1 company. All rights reserved. Dulux® is not a sponsor of and has no affiliation with Formula 1 or the Wallabies. Purchase period: 26 Aug - 20 September 2019 • Redemption period: 26 Aug – 20 October 2019 • Activation period: 26 Aug - 20 November 2019<br/>
                      *Requires internet & compatible device. Data charges apply. Kayo Basic package only. NSW Permit No.LTPS/19/36289 • ACT Permit No. TP19/03769 • SA Permit No. T19/1165</td>
                    </tr>
                  </table>
                </td>
              </tr>

            <!-- END MAIN CONTENT AREA -->
            </table>


          <!-- END CENTERED WHITE CONTAINER -->
          </div>
        </td>
        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
      </tr>
    </table>
  </body>
</html>