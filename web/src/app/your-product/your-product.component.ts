import { NgFor, NgIf } from '@angular/common';
import { Component, Input } from '@angular/core';
import { FormsModule } from '@angular/forms'; // <-- Import FormsModule

@Component({
  selector: 'app-your-product',
  imports: [FormsModule, NgIf, NgFor],
  templateUrl: './your-product.component.html',
  styleUrls: ['./your-product.component.css']
})
export class YourProductComponent {
  @Input() imageUrl!: string;
  @Input() title!: string;
  @Input() price!: number;
  @Input() rating!: number;
  
  showModal: boolean = false;
  newItem: string = '';
  newPrice: number = 0;
  description: string = '';
  showCategoryDetails: boolean = false;
  products: { imageUrl: string, title: string, price: number, rating: number }[] = [];

  toggleCategoryDetails() {
    this.showCategoryDetails = !this.showCategoryDetails;
  }

  openAddItem() {
    this.showModal = true;
  }

  closeModal() {
    this.showModal = false;
  }

  onFileSelected(event: Event) {
    const fileInput = event.target as HTMLInputElement;
    if (fileInput.files && fileInput.files[0]) {
      const file = fileInput.files[0];
      // Handle file upload logic here
    }
  }

  addItem() {
    if (this.newItem.trim()) {
      this.products.push({
        imageUrl: 'assets/image-placeholder.png', // Placeholder image URL
        title: this.newItem,
        price: this.newPrice,
        rating: 0 // Default rating
      });
      this.newItem = ''; // Clear input
      this.newPrice = 0;
      this.description = '';
    }
    this.closeModal();
  }

  addColor() {
    // Logic to add color
    alert('Color added');
  }

  addSize() {
    // Logic to add size
    alert('Size added');
  }

  addStock() {
    // Logic to add stock
    alert('Stock added');
  }
}