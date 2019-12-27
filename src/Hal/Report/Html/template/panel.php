<?php
$fullwidth = true;
// If maintainability index is requested to be without comments, this is true. False otherwise.
$modeMIWOC = $config->has('maintainability-index-without-comments');
require __DIR__ . '/_header.php'; ?>

    <div class="row">
        <div class="column">
            <div class="bloc bloc-number">
                <div class="number"><?php echo $sum->loc; ?></div>
                <div class="label">lines of code <?php echo $this->getTrend('sum', 'loc'); ?></div>
            </div>
        </div>
        <div class="column">
            <div class="bloc bloc-number">
                <div class="number">
                    <?php echo $sum->nbClasses; ?>
                    <small> (<?php echo(count($classes) ? round($sum->nbClasses / count($classes) * 100) : '0'); ?>
                        %)
                    </small>
                </div>
                <div class="label">classes <?php echo $this->getTrend('sum', 'nbClasses'); ?></div>
            </div>
        </div>
        <div class="column">
            <div class="bloc bloc-number">
                <div class="number"><?php echo $sum->nbInterfaces; ?>
                    <small> (<?php echo(count($classes) ? round($sum->nbInterfaces / count($classes) * 100) : '0'); ?>
                        %)
                    </small>
                </div>
                <div class="label">interfaces <?php echo $this->getTrend('sum', 'nbInterfaces'); ?></div>
            </div>
        </div>


    </div>

    <div class="row">

        <div class="column">
            <div class="bloc bloc-number">
                <div
                    class="number"><?php echo $sum->nbClasses ? round($sum->nbMethods / $sum->nbClasses) : '-'; ?></div>
                <div class="label">methods by class</div>
            </div>
        </div>
        <div class="column">
            <div class="bloc bloc-number">
                <div class="number"><?php echo $sum->nbClasses ? round($sum->lloc / $sum->nbClasses) : '-'; ?></div>
                <div class="label">logical lines of code by class</div>
            </div>
        </div>


        <div class="column">
            <div class="bloc bloc-number">
                <div class="number"><?php echo $sum->nbMethods ? round($sum->lloc / $sum->nbMethods) : '-'; ?></div>
                <div class="label">logical lines of code by method</div>
            </div>
        </div>

        <div class="column">
            <div class="bloc bloc-number">
                <div
                    class="number"><?php echo $avg->lcom ?></div>
                <div class="label">average LCOM <?php echo $this->getTrend('avg', 'lcom', true); ?></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="column column-50">
            <div class="bloc bloc-number">
                <div class="label">Top 10 ClassRank</div>
                <table id="table-pagerank">
                    <thead>
                    <tr>
                        <th>Class</th>
                        <th>ClassRank</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $classesS = $classes;
                    usort($classesS, static function ($a, $b) {
                        return strcmp($b['pageRank'], $a['pageRank']);
                    });
                    $classesS = array_slice($classesS, 0, 10);
                    foreach ($classesS as $class) { ?>
                        <tr>
                            <td><?php echo $class['name']; ?>
                                <?php
                                if ($modeMIWOC) {
                                    $badgeTitle = 'Maintainability Index (w/o comments)';
                                    $mi = isset($class['mIwoC']) ? $class['mIwoC'] : '';
                                } else {
                                    $badgeTitle = 'Maintainability Index';
                                    $mi = isset($class['mi']) ? $class['mi'] : '';
                                }
                                ?>
                                <span class="badge" title="<?php echo $badgeTitle; ?>"><?php echo $mi; ?></span>
                            </td>
                            <td><?php echo $class['pageRank']; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

            </div>

        </div>
        <div class="column">
            <div class="bloc bloc-number">
                <div class="number">
                    <?php echo $avg->ccn; ?>
                </div>
                <div class="label">Average cyclomatic complexity by class <?php echo $this->getTrend('avg', 'ccn',
                        true); ?></div>
            </div>
        </div>
        <div class="column">
            <div class="bloc">
                <h4>Maintainability / complexity</h4>
                <div id="svg-maintainability"></div>
            </div>
        </div>



    </div>

    <script type="text/javascript">
        document.onreadystatechange = function () {
            if (document.readyState === 'complete') {
                <?php
                $withoutCommentsArg = $modeMIWOC ? 'true' : 'false';
                ?>
                chartMaintainability(<?php echo $withoutCommentsArg ?>);
            }
        };
    </script>

<?php
require __DIR__ . '/_footer.php'; ?>
