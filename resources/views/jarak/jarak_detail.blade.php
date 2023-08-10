@include('layout.header')
@include('layout.navbar')
@include('layout.sidebar')

<div class="page-body">
    <!-- Container-fluid starts-->
        <div class="container-fluid">
<div class="page-header">
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                    <h5>Jarak Lokasi {{ $driver->user->name }}  </h5>
                </div>
                <div class="card-body" style="padding-top: 2px;">
                    <div class="table-responsive">
                        <table class="display" id="basic-2">
                                        <thead>
                                            <tr>
                                                <th>Lokasi</th>
                                                @foreach ($locations as $location)
                                                    <th>{{ $location->name }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($locations as $location)
                                            <tr>
                                                <td>{{ $location->name }}</td>
                                                @foreach ($locations as $destLocation)
                                                    <td>

                                                            <div class="edit-distance" data-loc1="{{ $location->id }}" data-loc2="{{ $destLocation->id }}">
                                                                <span class="distance-value" style="display: block;">
                                                                    @if (isset($jarak[$location->id][$destLocation->id]))
                                                                        {{ $jarak[$location->id][$destLocation->id] }} km
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </span>

                                                                <div class="edit-form" style="display: none;">
                                                                    <form action="" method="post">
                                                                        @csrf
                                                                        <input type="hidden" name="loc_1" value="{{ $location->id }}">
                                                                        <input type="hidden" name="loc_2" value="{{ $destLocation->id }}">
                                                                        <input type="number" step="any" name="distance"
                                                                            value="{{ $jarak[$location->id][$destLocation->id] ?? '' }}">
                                                                        <button type="submit">Simpan</button>

                                                                    </form>

                                                                </div>
                                                            </div>

                                                  
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


</div></div>
</div></div>
</div></div>
</div></div>
</div></div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editDistanceElements = document.querySelectorAll('.edit-distance');

        editDistanceElements.forEach(element => {
            const distanceValue = element.querySelector('.distance-value');
            const editForm = element.querySelector('.edit-form');

            distanceValue.addEventListener('click', function() {
                distanceValue.style.display = 'none';
                editForm.style.display = 'block';
            });

            // Mengatasi form disembunyikan saat form diklik
            editForm.addEventListener('click', function(event) {
                event.stopPropagation();
            });

            editForm.addEventListener('submit', function(event) {
                event.preventDefault(); // Mencegah form mengirim permintaan HTTP biasa

                const form = event.target;
                const formData = new FormData(form);

                fetch('/simpan/jarak', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const distanceInput = form.querySelector('input[name="distance"]');
                        distanceValue.textContent = distanceInput.value + ' km';
                        distanceValue.style.display = 'block';
                        editForm.style.display = 'none';
                    } else {
                        // Handle error case if needed
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });

        // Menyembunyikan form saat mengklik di luar form atau kolom
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.edit-distance')) {
                editDistanceElements.forEach(element => {
                    const distanceValue = element.querySelector('.distance-value');
                    const editForm = element.querySelector('.edit-form');

                    distanceValue.style.display = 'block';
                    editForm.style.display = 'none';
                });
            }
        });
    });
</script>


    @include('layout.footer')
    @include('layout.js')
