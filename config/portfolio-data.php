<?php
// Prépare les données affichées sur la page d'accueil (compétences + carrousel).

function portfolio_slug($name)
{
    $slug = mb_strtolower(trim($name), 'UTF-8');
    $slug = preg_replace('/\s+/', '-', $slug);

    // VS Code devient "vscode" pour le JavaScript
    if ($slug === 'vs-code') {
        return 'vscode';
    }

    // Procreate Dreams -> procreate-dreams
    if ($slug === 'procreate-dreams' || $slug === 'procreate-dream') {
        return 'procreate-dreams';
    }

    return $slug;
}

function portfolio_sort_works($works, $patterns)
{
    if (empty($patterns) || count($works) < 2) {
        return $works;
    }

    usort($works, function ($a, $b) use ($patterns) {
        $rankA = portfolio_work_rank($a, $patterns);
        $rankB = portfolio_work_rank($b, $patterns);
        if ($rankA === $rankB) {
            return 0;
        }
        return ($rankA < $rankB) ? -1 : 1;
    });

    return $works;
}

function portfolio_work_rank($work, $patterns)
{
    $haystack = mb_strtolower($work['alt'] . ' ' . $work['src'], 'UTF-8');

    foreach ($patterns as $index => $pattern) {
        if (strpos($haystack, mb_strtolower($pattern, 'UTF-8')) !== false) {
            return $index;
        }
    }

    return PHP_INT_MAX;
}

function build_portfolio_apps(PDO $bdd)
{
    $apps = array();

    // 1. Compétences affichées sur la page d'accueil
    $skills = $bdd->query('SELECT id, nom, image FROM skills ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);
    foreach ($skills as $skill) {
        if (empty($skill['image'])) {
            continue;
        }

        $slug = portfolio_slug($skill['nom']);
        $apps[$slug] = array(
            'name' => $skill['nom'],
            'icon' => 'images/' . $skill['image'],
        );
    }

    // 2. Oeuvres du carrousel (produits liés aux catégories)
    $categories = $bdd->query('SELECT id, name, image FROM categories ORDER BY name ASC')->fetchAll(PDO::FETCH_ASSOC);
    $products = $bdd->query(
        'SELECT p.id, p.name, p.cover, p.category
         FROM products p
         ORDER BY p.date DESC, p.id DESC'
    )->fetchAll(PDO::FETCH_ASSOC);

    $productsByCategory = array();
    foreach ($products as $product) {
        $productsByCategory[$product['category']][] = $product;
    }

    $galleryByProduct = array();
    $galleryStmt = $bdd->query('SELECT id_product, fichier FROM images ORDER BY id ASC');
    while ($row = $galleryStmt->fetch(PDO::FETCH_ASSOC)) {
        $galleryByProduct[$row['id_product']][] = $row['fichier'];
    }

    foreach ($categories as $category) {
        if (empty($productsByCategory[$category['id']])) {
            continue;
        }

        $slug = portfolio_slug($category['name']);
        $works = array();

        foreach ($productsByCategory[$category['id']] as $product) {
            if (!empty($product['cover'])) {
                $works[] = array(
                    'src' => 'images/' . $product['cover'],
                    'alt' => $product['name'] . ' — Paul Leroy',
                );
            }

            if (!empty($galleryByProduct[$product['id']])) {
                foreach ($galleryByProduct[$product['id']] as $fichier) {
                    if ($fichier === $product['cover']) {
                        continue;
                    }

                    $works[] = array(
                        'src' => 'images/' . $fichier,
                        'alt' => $product['name'] . ' — Paul Leroy',
                    );
                }
            }
        }

        if (empty($works)) {
            continue;
        }

        // Ordre personnalisé pour Figma
        if ($slug === 'figma') {
            $works = portfolio_sort_works($works, array('himalaya', 'couleur', 'liège', 'liege', 'festival'));
        }

        if (!isset($apps[$slug])) {
            $apps[$slug] = array(
                'name' => $category['name'],
                'icon' => 'images/' . $category['image'],
            );
        }

        $apps[$slug]['name'] = $category['name'];
        $apps[$slug]['icon'] = 'images/' . $category['image'];
        $apps[$slug]['works'] = $works;
    }

    return $apps;
}
