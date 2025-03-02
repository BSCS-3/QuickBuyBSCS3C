import { ComponentFixture, TestBed } from '@angular/core/testing';

import { YourProductComponent } from './your-product.component';

describe('YourProductComponent', () => {
  let component: YourProductComponent;
  let fixture: ComponentFixture<YourProductComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [YourProductComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(YourProductComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
