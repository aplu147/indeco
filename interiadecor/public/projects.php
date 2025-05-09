<?php
require_once '../includes/config.php';
require_once '../includes/header.php';

$pageTitle = "Our Projects - Interia Decor";

// Get all published projects
$db->query('SELECT * FROM projects WHERE status = "published" ORDER BY project_date DESC');
$projects = $db->resultSet();

// Get project categories for filtering
$db->query('SELECT DISTINCT location FROM projects WHERE status = "published" ORDER BY location');
$locations = $db->resultSet();
?>

<section class="page-header parallax-window" data-parallax="scroll" data-image-src="<?= SITE_URL ?>/assets/images/projects-bg.jpg">
    <div class="container">
        <h1>Our Projects</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= SITE_URL ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Projects</li>
            </ol>
        </nav>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">Recent Work</h2>
            <p class="section-subtitle">Explore our portfolio of completed projects</p>
        </div>
        
        <!-- Project Filters -->
        <div class="project-filters mb-5">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="search-filter">
                        <input type="text" id="project-search" class="form-control" placeholder="Search projects...">
                    </div>
                </div>
                <div class="col-md-6">
                    <select id="location-filter" class="form-select">
                        <option value="all">All Locations</option>
                        <?php foreach ($locations as $location): ?>
                        <option value="<?= htmlspecialchars($location->location) ?>"><?= htmlspecialchars($location->location) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Projects Grid -->
        <div class="row" id="projects-container">
            <?php foreach ($projects as $project): 
                $gallery = json_decode($project->gallery_images, true) ?: [];
            ?>
            <div class="col-md-6 col-lg-4 mb-4 project-item" 
                 data-title="<?= htmlspecialchars(strtolower($project->title)) ?>"
                 data-location="<?= htmlspecialchars(strtolower($project->location)) ?>">
                <div class="project-card">
                    <div class="project-image">
                        <img src="<?= SITE_URL ?>/assets/uploads/<?= htmlspecialchars($project->featured_image) ?>" alt="<?= htmlspecialchars($project->title) ?>" class="img-fluid">
                        <div class="project-overlay">
                            <h3><?= htmlspecialchars($project->title) ?></h3>
                            <p class="project-location"><?= htmlspecialchars($project->location) ?></p>
                            <a href="#project-<?= $project->id ?>" class="btn btn-outline-light" data-bs-toggle="modal">View Details</a>
                        </div>
                    </div>
                </div>
                
                <!-- Project Modal -->
                <div class="modal fade" id="project-<?= $project->id ?>" tabindex="-1" aria-labelledby="projectModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="projectModalLabel"><?= htmlspecialchars($project->title) ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="project-gallery">
                                            <div class="main-image mb-3">
                                                <img src="<?= SITE_URL ?>/assets/uploads/<?= htmlspecialchars($project->featured_image) ?>" alt="<?= htmlspecialchars($project->title) ?>" class="img-fluid">
                                            </div>
                                            <?php if (!empty($gallery)): ?>
                                            <div class="gallery-thumbs">
                                                <?php foreach ($gallery as $image): ?>
                                                <div class="thumb-item">
                                                    <img src="<?= SITE_URL ?>/assets/uploads/<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($project->title) ?>" class="img-fluid">
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="project-details">
                                            <h4>Project Details</h4>
                                            <ul class="project-meta">
                                                <li><strong>Client:</strong> <?= htmlspecialchars($project->client_name) ?></li>
                                                <li><strong>Location:</strong> <?= htmlspecialchars($project->location) ?></li>
                                                <li><strong>Date:</strong> <?= date('F Y', strtotime($project->project_date)) ?></li>
                                            </ul>
                                            <div class="project-description">
                                                <h5>Description</h5>
                                                <p><?= htmlspecialchars($project->description) ?></p>
                                            </div>
                                            <div class="project-services">
                                                <h5>Services Provided</h5>
                                                <ul>
                                                    <?php 
                                                    $services = explode(',', $project->services);
                                                    foreach ($services as $service): 
                                                        if (!empty(trim($service))):
                                                    ?>
                                                    <li><?= htmlspecialchars(trim($service)) ?></li>
                                                    <?php 
                                                        endif;
                                                    endforeach; 
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <a href="<?= SITE_URL ?>/contact.php" class="btn btn-primary">Start Your Project</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if (empty($projects)): ?>
        <div class="alert alert-info text-center">
            <p>No projects found. Please check back later.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
$(document).ready(function() {
    // Project filtering
    $('#project-search, #location-filter').on('keyup change', function() {
        const searchTerm = $('#project-search').val().toLowerCase();
        const locationFilter = $('#location-filter').val().toLowerCase();
        
        $('.project-item').each(function() {
            const title = $(this).data('title');
            const location = $(this).data('location');
            
            const titleMatch = title.includes(searchTerm);
            const locationMatch = locationFilter === 'all' || location.includes(locationFilter);
            
            if (titleMatch && locationMatch) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
    
    // Initialize gallery thumbs
    $('.gallery-thumbs').each(function() {
        const $thumbs = $(this);
        const $mainImage = $thumbs.siblings('.main-image').find('img');
        
        $thumbs.find('.thumb-item img').click(function() {
            const src = $(this).attr('src');
            $mainImage.attr('src', src);
            $thumbs.find('.thumb-item').removeClass('active');
            $(this).parent().addClass('active');
        });
        
        // Activate first thumb
        $thumbs.find('.thumb-item:first').addClass('active');
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>