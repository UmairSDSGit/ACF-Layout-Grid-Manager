/**
 * ACF Layout Grid Manager - Admin Script
 * Enhances ACF Flexible Content with customizable layout preview grids
 */
(function($) {
    // Wait until DOM is ready
    $(document).ready(function() {
        const config = window.acfLayoutGridManager || {};
        const { layouts, settings, placeholder } = config;
        
        // Create a mutation observer to watch for the popup insertion
        const observer = new MutationObserver(function(mutationsList) {
            mutationsList.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1 && node.classList.contains('acf-fc-popup')) {
                        enhanceLayoutPopup(node);
                    }
                });
            });
        });

        // Start observing the document body
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        /**
         * Enhances the ACF flexible content popup with a grid layout
         * @param {HTMLElement} popup - The ACF popup element
         */
        function enhanceLayoutPopup(popup) {
            const $popup = $(popup);
            const $ul = $popup.find('ul');
            
            // Skip if already enhanced
            if ($ul.hasClass('acf-lgm-grid-container')) return;
            
            // Add plugin-specific class to the tooltip popup
            $popup.addClass('acf-lgm-popup');
            
            // Clear existing content and add our classes
            $ul.empty().addClass('acf-lgm-grid-container');
            
            // Apply grid columns from settings
            $ul.css({
                'grid-template-columns': `repeat(${settings.grid_columns || 4}, minmax(160px, 1fr))`
            });
            
            // Add header with close button
            $popup.prepend(`
                <div class="acf-lgm-grid-header">
                    <h3 class="acf-lgm-grid-title">Choose a Layout</h3>
                    <a href="#" class="acf-lgm-grid-close">&times;</a>
                </div>
            `);
            
            // Close button functionality
            $popup.on('click', '.acf-lgm-grid-close', function(e) {
                e.preventDefault();
                acf.getPopup('fc').hide();
            });
            
            // Add each layout to the grid
            $.each(layouts, function(layoutName, layoutData) {
                const layoutLabel = formatLayoutName(layoutName);
                const imageUrl = layoutData.image || placeholder;
                const description = layoutData.description || '';
                
                const $li = $(`
                    <li class="acf-lgm-grid-item">
                        <a href="#" data-layout="${layoutName}" title="${layoutLabel}">
                            <img src="${imageUrl}" 
                                 alt="${layoutLabel}" 
                                 class="acf-lgm-grid-item-image"
                                 style="height: ${settings.item_height || 100}px; 
                                        background-color: ${settings.bg_color || '#ffffff'}" />
                            <span class="acf-lgm-grid-item-title">${layoutLabel}</span>
                            ${description ? `<span class="acf-lgm-grid-item-desc">${description}</span>` : ''}
                        </a>
                    </li>
                `);
                
                // Add hover effect if enabled
                if (settings.hover_effect !== false) {
                    $li.find('a').hover(
                        function() { $(this).css('border-color', '#2271b1'); },
                        function() { $(this).css('border-color', '#ddd'); }
                    );
                }
                
                $ul.append($li);
            });
            
            // Bind click event to layout items
            $ul.off('click', 'a').on('click', 'a', function(e) {
                e.preventDefault();
                const layout = $(this).data('layout');
                acf.getPopup('fc').select(layout);
            });
        }
        
        /**
         * Formats layout names for display (converts underscores to spaces and capitalizes)
         * @param {string} name - The layout name to format
         * @return {string} The formatted layout name
         */
        function formatLayoutName(name) {
            return name.replace(/_/g, ' ')
                      .replace(/\b\w/g, c => c.toUpperCase())
                      .replace(/\bAnd\b/g, '&');
        }
    });
})(jQuery);