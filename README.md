# BlogArchi

![Laravel](https://img.shields.io/badge/Laravel-8.x-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.1-blue?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-5.7-blue?logo=mysql)
![License](https://img.shields.io/badge/License-MIT-green)

## 🌟 À propos

**BlogArchi** est une application de blogging développée avec **Laravel**, conçue pour démontrer les **bonnes pratiques professionnelles** :  
- Architecture **Service & Repository Pattern**  
- Transactions atomiques  
- Gestion asynchrone avec **Events & Queues**  
- Sécurité avec **Policies Laravel**  
- Tests automatisés avec **PHPUnit & Pest**  

---

## 🧩 Objectifs

- Code maintenable, testable et découplé  
- Optimisation des performances (Eager Loading, Queue)  
- Sécurité renforcée  
- Respect des standards professionnels Laravel  

---

## ⚙️ Architecture

- **PostController** : logique HTTP (validation, redirection, vues)  
- **PostService** : logique métier, transactions, déclenchement d’événements  
- **PostRepository** : interaction avec la base de données (CRUD)  

**Transactions atomiques :**
```php
DB::beginTransaction();
try {
    $post = $this->postRepository->create($data);
    event(new PostCreated($post));
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
    Storage::delete($path);
    throw $e;
}
