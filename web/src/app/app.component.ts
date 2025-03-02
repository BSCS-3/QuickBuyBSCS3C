import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { YourProductComponent } from './your-product/your-product.component';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterOutlet, YourProductComponent],
  templateUrl: './app.component.html',
  styleUrl: './app.component.css'
})
export class AppComponent {
  title = 'QuickBuy';
}
