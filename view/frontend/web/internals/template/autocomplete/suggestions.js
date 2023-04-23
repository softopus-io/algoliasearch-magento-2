define([], function () {
    return {
        getNoResultHtml: function ({html}) {
            return html`<p>${algoliaConfig.translations.noResults}</p>`;
        },

        getHeaderHtml: function ({html}) {
            return html`<p>${algoliaConfig.translations.suggestions}</p>`;
        },

        getItemHtml: function ({item, components, html}) {
            const itemQuery = (item._highlightResult?.query?.value)
                ? components.Highlight({ hit: item, attribute: "query" })
                : item.query;

            return html`<a class="aa-ItemLink" href="${algoliaConfig.resultPageUrl}?q=${encodeURIComponent(item.query)}"
                data-objectId=${item.objectID} data-index=${item.__autocomplete_indexName} data-queryId=${item.__autocomplete_queryID}>
                ${itemQuery}
            </a>`;
        },

        getFooterHtml: function () {
            return "";
        }
    };
});
