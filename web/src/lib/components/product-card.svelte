<script lang="ts">
    export let product: any;
</script>

<div role="button"  class="group p-4 flex flex-col gap-4 rounded-lg border border-transparent hover:border-black cursor-pointer transition-all duration-200 w-[280px] max-sm:w-[160px] max-lg:w-[200px]">
    <div  class="bg-accent aspect-square w-full flex items-center justify-center overflow-hidden relative" >
        <img src={product.image_url}  alt={product.name}  class="max-w-[80%] max-h-[80%] object-contain transform group-hover:scale-105 transition-transform duration-200"/>
    </div>

    <!-- Product Details -->
    <div class="mt-2 space-y-2 flex-1">
        <p class="text-base text-gray-600">{product.series}</p>
        <h3 class="font-medium text-lg font-afacad line-clamp-2 h-[3em]">
            {product.name}
        </h3>
        <p class="text-base font-semibold">
            ₱{Number(product.price).toLocaleString()}.00
        </p>
    </div>
</div>
