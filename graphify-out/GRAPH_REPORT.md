# Graph Report - .  (2026-07-24)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 345 nodes · 443 edges · 61 communities (58 shown, 3 thin omitted)
- Extraction: 94% EXTRACTED · 6% INFERRED · 0% AMBIGUOUS · INFERRED: 28 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- composer.json
- Inventory
- SupplyRequest
- Procurement
- Supplier
- scripts
- devDependencies
- Controller
- User
- Illuminate\Foundation\Http\FormRequest
- Illuminate\Http\Request
- AppServiceProvider
- TestCase
- ExampleTest
- app.blade.php

## God Nodes (most connected - your core abstractions)
1. `Inventory` - 23 edges
2. `SupplyRequest` - 23 edges
3. `Supplier` - 18 edges
4. `Procurement` - 15 edges
5. `SupplyRequestController` - 13 edges
6. `InventoryController` - 12 edges
7. `ProcurementController` - 12 edges
8. `SupplierController` - 11 edges
9. `Controller` - 10 edges
10. `SupplyRequestService` - 9 edges

## Surprising Connections (you probably didn't know these)
- `InventoryController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/InventoryController.php → app/Http/Controllers/Controller.php
- `ProcurementController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/ProcurementController.php → app/Http/Controllers/Controller.php
- `SupplierController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/SupplierController.php → app/Http/Controllers/Controller.php
- `SupplyRequestController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/SupplyRequestController.php → app/Http/Controllers/Controller.php
- `LoginController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Auth/LoginController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (61 total, 3 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.05
Nodes (40): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+32 more)

### Community 1 - "Inventory"
Cohesion: 0.10
Nodes (5): InventoryController, UpdateInventoryRequest, Inventory, StockLog, InventoryService

### Community 2 - "SupplyRequest"
Cohesion: 0.10
Nodes (4): SupplyRequestController, StoreSupplyRequest, SupplyRequest, SupplyRequestService

### Community 3 - "Procurement"
Cohesion: 0.12
Nodes (6): ProcurementController, Notification, Procurement, SupplierTransaction, ProcurementService, Illuminate\Database\Eloquent\Model

### Community 4 - "Supplier"
Cohesion: 0.11
Nodes (6): SupplierController, StoreSupplierRequest, UpdateSupplierRequest, Supplier, SupplierService, Illuminate\Database\Eloquent\Builder

### Community 5 - "scripts"
Cohesion: 0.08
Nodes (26): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+18 more)

### Community 6 - "devDependencies"
Cohesion: 0.09
Nodes (21): alpinejs, axios, concurrently, laravel-vite-plugin, devDependencies, alpinejs, axios, concurrently (+13 more)

### Community 7 - "Controller"
Cohesion: 0.15
Nodes (5): LoginController, LogoutController, Controller, DashboardController, DashboardService

### Community 8 - "User"
Cohesion: 0.15
Nodes (9): User, UserFactory, DatabaseSeeder, Illuminate\Database\Console\Seeds\WithoutModelEvents, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Database\Seeder, Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable (+1 more)

### Community 9 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.16
Nodes (4): LoginRequest, StoreInventoryRequest, StoreProcurementRequest, Illuminate\Foundation\Http\FormRequest

### Community 10 - "Illuminate\Http\Request"
Cohesion: 0.26
Nodes (5): AdminMiddleware, RoleMiddleware, Closure, Illuminate\Http\Request, Symfony\Component\HttpFoundation\Response

### Community 12 - "TestCase"
Cohesion: 0.40
Nodes (3): Illuminate\Foundation\Testing\TestCase, ExampleTest, TestCase

## Knowledge Gaps
- **59 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+54 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **3 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `SupplyRequest` connect `SupplyRequest` to `Illuminate\Http\Request`, `Procurement`, `Controller`?**
  _High betweenness centrality (0.045) - this node is a cross-community bridge._
- **Why does `Inventory` connect `Inventory` to `Procurement`, `Controller`?**
  _High betweenness centrality (0.045) - this node is a cross-community bridge._
- **Why does `Controller` connect `Controller` to `Inventory`, `SupplyRequest`, `Procurement`, `Supplier`?**
  _High betweenness centrality (0.034) - this node is a cross-community bridge._
- **Are the 12 inferred relationships involving `SupplyRequest` (e.g. with `.approve()` and `.destroy()`) actually correct?**
  _`SupplyRequest` has 12 INFERRED edges - model-reasoned connections that need verification._
- **Are the 12 inferred relationships involving `Supplier` (e.g. with `.create()` and `.edit()`) actually correct?**
  _`Supplier` has 12 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _59 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.04878048780487805 - nodes in this community are weakly interconnected._