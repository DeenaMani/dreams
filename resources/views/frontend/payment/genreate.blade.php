<form action="{{ route('razorpay.payment.store') }}" method="POST" >
			            @csrf 
			            <script src="https://checkout.razorpay.com/v1/checkout.js"
			                    data-key="{{ env('RAZORPAY_KEY') }}"
			                    data-amount="1000000"
			                    data-buttontext="Pay 100000 INR"
			                    data-name="GeekyAnts official"
			                    data-description="Razorpay payment"
			                    data-image="/images/logo-icon.png"
			                    data-prefill.name="ABC"
			                    data-prefill.email="abc@gmail.com"
			                    data-theme.color="#ff7529">
			            </script>
		        	</form>