@if (session()->has('type') && session('type') == 'toast' && session()->has('message') && session()->has('status'))
    @switch(session('status'))
        @case('error')
            <script>
                toastr.options = {
                    "positionClass": "toast-bottom-right"
                };
                toastr.error(
                    "{{ session('message') }}",
                );
            </script>
        @break

        @case('success')
            <script>
                toastr.options = {
                    "positionClass": "toast-bottom-right"
                };
                toastr.success(
                    "{{ session('message') }}",
                );
            </script>
        @break

        @case('warning')
            <script>
                toastr.options = {
                    "positionClass": "toast-bottom-right"
                };
                toastr.warning(
                    "{{ session('message') }}",
                );
            </script>
        @break

        @case('info')
            <script>
                toastr.options = {
                    "positionClass": "toast-bottom-right"
                };
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
<script>
    // Helper function to format time for display
    function formatTimeForDisplay(timeString) {
        // If time is in format HH:MM:SS, remove seconds
        if (timeString.includes(':')) {
            const parts = timeString.split(':');
            if (parts.length === 3) {
                timeString = parts[0] + ':' + parts[1]; // Remove seconds
            }
        }

        // Convert 24-hour format to 12-hour format with AM/PM for better readability
        const hour = parseInt(timeString.split(':')[0], 10);
        const minute = timeString.split(':')[1] || '00';

        // Add period context based on time
        let periodLabel = '';
        if (hour >= 5 && hour < 12) {
            periodLabel = ' <small class="text-muted">(Pagi)</small>';
        } else if (hour >= 12 && hour < 15) {
            periodLabel = ' <small class="text-muted">(Siang)</small>';
        } else if (hour >= 15 && hour < 18) {
            periodLabel = ' <small class="text-muted">(Sore)</small>';
        } else {
            periodLabel = ' <small class="text-muted">(Malam)</small>';
        }

        // Format in 12-hour with period label
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12; // Convert 0 to 12 for 12 AM

        return hour12 + ':' + minute + ' ' + ampm + periodLabel;
    }
    // function formatTimeForDisplay(timeString) {
    //     // Asumsi format waktu adalah "HH:MM:SS" atau "HH:MM"
    //     const timeParts = timeString.split(':');
    //     const hour = parseInt(timeParts[0]);
    //     const minute = parseInt(timeParts[1]);

    //     // Format 24 jam dengan leading zero
    //     const formatted = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;

    //     // Tambahkan konteks waktu (pagi, siang, sore, malam)
    //     let timeContext = '';
    //     if (hour >= 0 && hour < 12) {
    //         timeContext = 'pagi';
    //     } else if (hour >= 12 && hour < 15) {
    //         timeContext = 'siang';
    //     } else if (hour >= 15 && hour < 18) {
    //         timeContext = 'sore';
    //     } else {
    //         timeContext = 'malam';
    //     }

    //     return `${formatted} <small class="text-muted">${timeContext}</small>`;
    // }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

  
</script>
