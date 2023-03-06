<?php

declare(strict_types=1);


namespace Drupal\server_general;

use Drupal\Core\Url;

/**
 * Helper methods for rendering different elements.
 *
 * In this trait an "element" signifies a section or a strip on the page. That
 * element can be for example a Related content carousel or a CTA.
 * element that.
 */
trait ElementTrait {

  use ButtonTrait;
  use ElementWrapTrait;
  use LinkTrait;
  use TitleAndLabelsTrait;

  /**
   * Build a CTA.
   *
   * @param string $title
   *   The title.
   * @param array $text
   *   Processed text.
   * @param string $button_text
   *   The button text.
   * @param \Drupal\Core\Url $url
   *   The URL to link the button to.
   *
   * @return array
   *   Render array.
   */
  protected function buildElementCta(string $title, array $text, string $button_text, Url $url): array {
    $elements = [];

    // Title.
    $element = ['#markup' => $title];
    $element = $this->wrapTextResponsiveFontSize($element, '3xl');
    $elements[] = $this->wrapTextFontWeight($element, 'bold');

    // Text.
    $elements[] = $text;

    // Button.
    $elements[] = $this->buildButton($button_text, $url);

    $elements = $this->wrapContainerVerticalSpacingBig($elements, 'center');

    return [
      '#theme' => 'server_theme_element__cta',
      '#items' => $elements,
    ];
  }

  /**
   * Build a Hero image.
   *
   * @param array $image
   *   The render array of the image.
   * @param string $title
   *   The title.
   * @param string $subtitle
   *   The subtitle.
   * @param string $button_text
   *   The button text.
   * @param \Drupal\Core\Url $url
   *   The URL to link the button to.
   *
   * @return array
   *   Render array.
   */
  protected function buildElementHeroImage(array $image, string $title, string $subtitle, string $button_text, Url $url): array {
    $elements = [];

    // Title.
    $element = ['#markup' => $title];
    $element = $this->wrapHtmlTag($element, 'h1');
    $elements[] = $this->wrapTextFontWeight($element, 'bold');

    // Subtitle.
    $element = ['#markup' => $subtitle];
    $element = $this->wrapTextResponsiveFontSize($element, 'xl');
    $elements[] = $this->wrapTextFontWeight($element, 'medium');

    // Button.
    $elements[] = $this->buildButton($button_text, $url);

    $elements = $this->wrapContainerVerticalSpacingBig($elements);

    return [
      '#theme' => 'server_theme_element__hero_image',
      '#image' => $image,
      '#items' => $elements,
    ];
  }


  /**
   * Builds a "Document" list.
   *
   * @param string $title
   *   The title.
   * @param array $documents
   *   Render array of documents.
   * @param string $color_scheme
   *   The color scheme.
   *
   * @return array
   *   Render array.
   */
  protected function buildElementDocuments(string $title, array $documents, string $color_scheme): array {
    $elements = [];

    // Title.
    $elements[] = $this->buildParagraphTitle($title);

    // Items and "View more" button.
    $button = $this->buildButton($this->t('View more'), Url::fromUserInput('#'));
    $elements[] = $this->buildElementItemsWithViewMore($documents, $button, 2, $color_scheme);

    return $this->wrapBackgroundColor($elements, 'light-gray');
  }

  /**
   * Build a Carousel.
   *
   * @param array $items
   *   The items to render inside the carousel.
   * @param bool $is_featured
   *   Determine if items inside the carousel are "featured". Usually a featured
   *   item means that only a single card should appear at a time.
   * @param string|null $title
   *   Optional; The title.
   * @param array|null $button
   *   Optional; The render array of the button, likely created with
   *   ButtonTrait::buildButton.
   *
   * @return array
   *   Render array.
   */
  protected function buildElementCarousel(array $items, bool $is_featured = FALSE, string $title = NULL, array $button = NULL): array {
    if (empty($items)) {
      return [];
    }

    return [
      '#theme' => 'server_theme_carousel',
      '#title' => $title,
      '#items' => $items,
      '#button' => $button,
      '#is_featured' => $is_featured,
    ];
  }

  /**
   * Render items with a View more button, that will reveal more items.
   *
   * @param array $items
   *   The items to render.
   * @param array $button
   *   The button with "View more".
   * @param int $limit_count
   *   Determine how many items to show initially.
   * @param string $color_scheme
   *   The color scheme.
   *
   * @return array
   *   The render array.
   */
  protected function buildElementItemsWithViewMore(array $items, array $button, int $limit_count, string $color_scheme): array {
    if (count($items) <= $limit_count) {
      // We don't need to hide any item.
      return $items;
    }

    $wrapped_items = [];
    foreach (array_values($items) as $key => $item) {
      if ($key > $limit_count) {
        // Hide the items that are over the limit count.
        $item = $this->wrapHidden($item);
      }
      $wrapped_items[] = $item;
    }

    $elements = [];
    $elements[] = $wrapped_items;
    $elements[] = $button;
    $elements = $this->wrapContainerVerticalSpacing($elements);

    return [
      '#theme' => 'server_theme_element_items_with_view_more',
      '#items' => $elements,
    ];
  }

}
