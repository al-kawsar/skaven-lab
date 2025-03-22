@if (session()->has('type') && session('type') == 'toast' && session()->has('message') && session()->has('status'))
@switch(session('status'))
@case('error')
<script>
    toastr.error(
        "{{ session('message') }}",
        );
    </script>
    @break

    @case('success')
    <script>
        toastr.success(
            "{{ session('message') }}",
            );
        </script>
        @break

        @case('warning')
        <script>
            toastr.warning(
                "{{ session('message') }}",
                );
            </script>
            @break

            @case('info')
            <script>
                toastr.info(
                    "{{ session('message') }}",
                    );
                </script>
                @break

                @default
                @endswitch
                @elseif(session()->has('errors'))
                <script>
                    Swal.fire({
                        title: 'Error!',
                        text: "{{ $errors->first() }}",
                        icon: 'error'
                    });
                </script>
                @endif
