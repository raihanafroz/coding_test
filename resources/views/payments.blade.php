@extends('layouts.app')

@section('stylesheet')
@endsection


@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Your Payments</div>

          <div class="card-body">
            @if (session('status'))
              <div class="alert alert-success" role="alert">
                {{ session('status') }}
              </div>
            @endif
            <div class="row">
              <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                  <div class="panel-body">
                    <table class="table table-striped table-bordered dt-responsive nowrap" style="min-width: 100%">
                      <thead>
                      <th>#</th>
                      <th>Amount</th>
                      <th>Card</th>
                      <th>Created_at</th>
                      <th>Status</th>
                      </thead>
                      <tbody>
                      @foreach($payments as $payment)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $payment->amount }} USD</td>
                          <td>{{ $payment->card_brand }}</td>
                          <td>{{ date('F d, Y h:i A', strtotime($payment->created_at)) }}</td>
                          <td class="text-capitalize">{{ $payment->status }}</td>
                        </tr>
                      @endforeach
                      </tbody>
                    </table>
                    <div class="row">
                      <div class="col-sm-12">
                        {{ $payments->links() }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')

  <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

  <script type="text/javascript">
    $(function () {
      var $form = $(".validation");
      $('form.validation').bind('submit', function (e) {
        var $form = $(".validation"),
          inputVal = ['input[type=email]', 'input[type=password]',
            'input[type=text]', 'input[type=file]',
            'textarea'].join(', '),
          $inputs = $form.find('.required').find(inputVal),
          $errorStatus = $form.find('div.error'),
          valid = true;
        $errorStatus.addClass('hide');

        $('.has-error').removeClass('has-error');
        $inputs.each(function (i, el) {
          var $input = $(el);
          if ($input.val() === '') {
            $input.parent().addClass('has-error');
            $errorStatus.removeClass('hide');
            e.preventDefault();
          }
        });

        if (!$form.data('cc-on-file')) {
          e.preventDefault();
          Stripe.setPublishableKey($form.data('stripe-publishable-key'));
          Stripe.createToken({
            number: $('.card-num').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val()
          }, stripeHandleResponse);
        }

      });

      function stripeHandleResponse(status, response) {
        if (response.error) {
          $('.error')
            .removeClass('hide')
            .find('.alert')
            .text(response.error.message);
        } else {
          var token = response['id'];
          $form.find('input[type=text]').empty();
          $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
          $form.get(0).submit();
        }
      }

    });
  </script>
@endsection
