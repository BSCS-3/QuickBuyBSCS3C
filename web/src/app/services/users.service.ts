import { Injectable } from '@angular/core';
import { ApiService } from './api.service';

interface User {
  id: number;
  username: string;
  email: string;
}

@Injectable({
  providedIn: 'root'
})

export class UsersService {
  constructor(private apiService: ApiService) {}

  getAllUsers() {
    return this.apiService.get('lahatnguser');
  }

  getUser(id: number) {
    return this.apiService.get(`user/${id}`);
  }

  registerUser(userData: Omit<User, 'id'>) {
    return this.apiService.post('registeruser', userData);
  }
}