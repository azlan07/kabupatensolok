<?php include "header.php"; ?>

<!-- Start about-info Area -->
<section class="about-info-area section-gap">
    <div class="panel-body">
        <table class="table table-bordered table-striped table-admin">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th width="30%">Nama Wisata</th>
                    <th width="30%">Alamat</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $data = file_get_contents('http://localhost/KABUPATENSOLOK/ambildatafasilitas.php');
                $no = 1;
                if (json_decode($data, true)) {
                    $obj = json_decode($data);
                    foreach ($obj->results as $item) {
                ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $item->nama_fasilitas; ?></td>
                            <td><?php echo $item->alamat; ?></td>
                            <td class="ctr">
                                <div class="btn-group">
                                    <a href="detailfasilitas.php?id_fasilitas=<?php echo $item->id_fasilitas; ?>" rel="tooltip" data-original-title="Lihat File" data-placement="top" class="btn btn-primary">
                                        <i class="fa fa-map-marker"> </i> Detail dan Lokasi</a>&nbsp;
                                </div>
                            </td>
                        </tr>
                <?php $no++;
                    }
                } else {
                    echo "data tidak ada.";
                } ?>

            </tbody>
        </table>
    </div>
    </div>
    </div>

    </div>
    </div>
    </div>
</section>
<!-- End about-info Area -->
<?php include "footer.php"; ?>