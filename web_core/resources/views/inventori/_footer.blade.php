
	</div>
	<footer class="main-footer text-center">
		<strong>Copyright &copy; {{date('Y')}} <a href="https://tipd.uinib.ac.id">TIPD UIN IB Padang</a>.</strong>
	</footer>
	</div>

	<div id="modal-profile" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					halo
				</div>
			</div>
		</div>
	</div>

	<script src="{{ url('assets/bo/plugins/jquery/jquery.min.js')}} "></script>
	<script src="{{ url('assets/bo/plugins/bootstrap/js/bootstrap.bundle.min.js')}} "></script>
	<script src="{{ url('assets/bo/plugins/moment/moment.min.js')}} "></script>
	<script src="{{ url('assets/bo/plugins/sweetalert2/sweetalert2.min.js') }} "></script>
	<script src="{{ url('assets/bo/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}} "></script>
	<script src="{{ url('assets/bo/plugins/daterangepicker/daterangepicker.js')}} "></script>
	<script src="{{ url('assets/bo/plugins/select2/js/select2.full.min.js')}} "></script>
	<script src="{{ url('assets/bo/jquery-number/jquery.number.min.js')}} "></script>
	@yield('addon_footer')
	<script src="{{ url('assets/bo/dist/js/adminlte.min.js')}} "></script>
	<script>
		let token = "{{ csrf_token() }} ";
		const loading = '<i class="fas fa-sync-alt fa-spin"></i> Memproses...';
		const Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 3000
		});
		function toast(type, text) {
			Toast.fire({
				icon: type,
				title: '<p class="text-left m-2">'+text+'</p>'
			});
		}
		function toasterr(datas) {
			const err = datas.responseJSON;
			toast('error',err.message);
		}

		$('.btn-profile').click(function(e){
			e.preventDefault();
			const elm = $(this).last();
			const ofs = elm.offset();
			const top = ofs.top;
			const left = ofs.left + 50;
			$('.modal-content').css({"top": "10vh", "right": "-2vw"});
			$('#modal-profile').modal('show');
		});
	</script>
	@yield('addonjs')
</body>
</html>
