<div class="alert alert-{{ $class }}">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	@if ($class == 'error')
        <p style="color: red">{{ $message }}</p>
    @else
        <p style="color: blue">{{ $message }}</p>
    @endif
</div>